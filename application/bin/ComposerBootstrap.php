<?php

use Composer\Script\Event;

class ComposerBootstrap
{
    /**
     * @param $event Event
     */
    public static function checkConfiguration($event)
    {
        if (static::ensureConfig()) {
            static::createDatabase();
            static::resetRootLogin();
            static::importExampleApplications();
            static::updateEpsgCodes();
        }

        static::clearCache();
    }

    /**
     * @param $event Event
     */
    public static function bootstrapDatabase($event)
    {
        static::ensureConfig();
        $status = null;
        passthru("php bin/console doctrine:schema:create", $status);
        if ($status === 0) {
            `php bin/console mapbender:database:init -v`;
            static::resetRootLogin();
        }
    }

    public static function reimportExampleApps()
    {
        static::importExampleApplications();
        static::clearCache();
    }

    /**
     * @return bool true if a config file needed to be created
     */
    protected static function ensureConfig(): bool
    {
        $files = [static::getParametersPath(), static::getLocalEnvFilePath()];

        $fileCreated = false;
        foreach ($files as $file) {
            if (!file_exists($file)) {
                copy("{$file}.dist", $file);
                $fileCreated = true;
            }
        }
        return $fileCreated;
    }

    /**
     * Rebuild database.
     * Needs for tests
     *
     * @param $event
     */
    public static function rebuildDatabase($event)
    {
        static::ensureConfig();
        static::dropDatabase();
        static::createDatabase();
        static::resetRootLogin();
        static::importExampleApplications();
        static::updateEpsgCodes();
        //static::clearCache();
    }

    /**
     * Is windows
     *
     * @return bool
     */
    public static function isWindows()
    {
        static $isWindows;
        if ($isWindows === null) {
            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        }
        return $isWindows;
    }

    /**
     * Clear cache but let sessions alive
     */
    public static function clearCache()
    {
        $cacheRoot = \getenv('MB_CACHE_DIR') ?: dirname(__FILE__) . '/../var/cache';
        $fs = new \Symfony\Component\Filesystem\Filesystem(); // NOTE: built into composer phar
        static::printStatus("Clear cache");
        foreach (glob($cacheRoot . "/*/*") as $path) {
            if (\basename($path) !== 'sessions') {
                $fs->remove($path);
            }
        }
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony bin/console assets:install).
     */
    public static function installAssets()
    {
        if (!file_exists(static::getParametersPath())) {
            throw new \RuntimeException("Application not configured");
        }

        $isWindows = static::isWindows();
        if ($isWindows) {
            static::installHardCopyAssets();
        } else {
            static::installSymLinkAssets();
        }
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony bin/console assets:install).
     */
    public static function installHardCopyAssets()
    {
        echo `php bin/console assets:install public`;
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony bin/console assets:install).
     */
    public static function installSymLinkAssets()
    {
        echo `php bin/console assets:install public --symlink --relative `;
    }

    /**
     * Enable write cache, logs and upload folder by user and group
     */
    public static function allowWriteLogs()
    {
        if (!static::isWindows()) {
            static::printStatus("Enable write cache, logs and upload for user and user group");

            echo `chmod -R ug+wX var/cache`;
            echo `chmod -R ug+wX var/log`;
            echo `chmod -R ug+wX public/uploads`;
        }
    }

    /**
     * Crate database, schema and force update
     */
    public static function dropDatabase()
    {
        static::printStatus("Drop database");

        echo `php bin/console doctrine:database:drop --force`;
    }

    /**
     * Create database, schema and force update
     */
    public static function createDatabase()
    {
        static::printStatus("Create and prepare database");

        echo `php bin/console doctrine:database:create`;
        echo `php bin/console doctrine:schema:create`;
        //echo `php bin/console doctrine:schema:update --force`;
    }

    /**
     * Reset root user login
     *
     * @param string $userName
     * @param string $password
     * @param string $userEmail
     */
    protected static function resetRootLogin($userName = "root", $password = "root", $userEmail = "root@localhost")
    {
        $userName = escapeshellarg($userName);
        $userEmail = escapeshellarg($userEmail);
        $password = escapeshellarg($password);

        static::printStatus("Reset user password");

        `php bin/console fom:user:resetroot --username $userName --password $password --email $userEmail --silent`;

        static::printStatus("ATTENTION");
        echo "User $userName account password is: $password. Don't forget to change it!\n";
    }

    /**
     * Get symfony root path
     *
     * @return string
     */
    protected static function getSymfonyRootPath()
    {
        static $path;
        if (!$path) {
            $path = realpath(__DIR__ . "/..");
        }
        return $path;
    }

    /**
     * Import or update EPSG codes
     */
    public static function updateEpsgCodes()
    {
        static::printStatus("Update EPSG codes");
        echo `php bin/console mapbender:database:init -v`;
    }

    /**
     * Import example applications from "app/config/mapbender.yaml" file
     */
    public static function importExampleApplications()
    {
        static::printStatus("Import example mapbender applications");
        \passthru("php bin/console mapbender:application:import " . dirname(__FILE__) . '/../config/applications');
    }

    /**
     * @param string $title
     */
    protected static function printStatus($title)
    {
        echo "\n[$title]\n";
    }

    /**
     * @param string $mode
     * @param string|null $stripPrefix
     * @return string
     */
    protected static function getVersion($mode = 'git', $stripPrefix = null)
    {
        if ($stripPrefix) {
            $version = static::getVersion($mode, null);
            $prefixPosition = strpos($version, $stripPrefix);
            if ($prefixPosition) {  // !== false && !== 0
                throw new \LogicException("Passed version prefix " . print_r($stripPrefix, true) . " occurs not as a prefix, but at offset {$prefixPosition} in version {$version}");
            } elseif ($prefixPosition === 0) {
                return substr($version, strlen($stripPrefix));
            } else {
                fwrite(STDERR, "WARNING: Passed version prefix " . print_r($stripPrefix, true) . " does not occur in version {$version}\n");
                return $version;
            }
        }
        return trim(@shell_exec('git describe --tags'));
    }

    /**
     * @return string
     */
    protected static function getParametersPath()
    {
        return static::getSymfonyRootPath() . '/config/parameters.yaml';
    }

    protected static function getLocalEnvFilePath()
    {
        return static::getSymfonyRootPath() . '/.env.local';
    }

    /**
     * @param Event $e
     */
    public static function distribute($e)
    {
        /** @var \Composer\Package\RootPackage $package */
        $package = $e->getComposer()->getPackage();
        $config = $package->getConfig();
        $archiveName = current(array_slice(explode("/", $package->getName()), -1));
        $archiveFormat = $config["archive-format"];
        $archivePath = $config["archive-dir"];
        $archiveVersion = static::getVersion('git', 'v');

        // Overwrite archive format, name and version if given
        $arguments = $e->getArguments();

        if (isset($arguments[0])) {
            $archiveFormat = $arguments[0];
        }

        if (isset($arguments[1])) {
            $archiveName = $arguments[1];
        }

        if (isset($arguments[2])) {
            $archiveVersion = $arguments[2];
        }

        $archiveFileName = "$archiveName-$archiveVersion";

        if (!is_dir($archivePath)) {
            mkdir($archivePath);
        }

        if ($archiveFormat == "zip") {
            static::installHardCopyAssets();
        } else {
            static::installSymLinkAssets();
        }

        $fullArchivePath = realpath($archivePath);
        $archiveProjectPath = "$fullArchivePath/$archiveFileName";
        $assetsPath = $archiveProjectPath . "/public/bundles";

        // Remove assets
        if (is_dir($assetsPath)) {
            echo `rm -rf "$assetsPath"`;
        }
        // Copy project files
        echo `cp -rf . $archiveProjectPath`;

        // Remove development files
        echo `find $archiveProjectPath -name "*.git*" | xargs rm -rf `;
        echo `find $archiveProjectPath -name "*.travis.yml" | xargs rm -rf `;
        echo `find $archiveProjectPath/vendor -type d -iname "tests" | xargs rm -rf `;
        echo `find $archiveProjectPath/vendor -type d -iname "demo" | xargs rm -rf `;

        foreach (array(
                     "vendor/mapbender/documentation",
                     "var/cache/*",
                     "var/log/*",
                 ) as $path) {
            echo `rm -rf $archiveProjectPath/{$path}`;
        }

        // Copy license and readme files
        echo `find vendor/ -type f -iname "license*" | xargs -I'{}' cp --parents '{}' "$archiveProjectPath"`;
        echo `find vendor/ -type f -iname "readme*" | xargs -I'{}' cp --parents '{}' "$archiveProjectPath"`;

        // Copy project info files
        echo `cp ../LICENSE "$archiveProjectPath/"`;
        echo `cp ../README.md "$archiveProjectPath/"`;
        echo `cp ../CONTRIBUTING.md "$archiveProjectPath/"`;
        echo `cp ../CHANGELOG.md "$archiveProjectPath/"`;

        echo "Distributed to: $archiveProjectPath\n";
        echo "CAUTION: The generated build contains all your database passwords. Do not publicly distribute the generated file. \n\n";
    }

    /**
     * @param Event $e
     * @return int
     */
    public static function build($e)
    {
        /** @var \Composer\Package\RootPackage $package */
        $package = $e->getComposer()->getPackage();
        $config = $package->getConfig();
        $archiveName = current(array_slice(explode("/", $package->getName()), -1));
        $archiveFormat = $config["archive-format"];
        $archivePath = $config["archive-dir"];
        $archiveVersion = static::getVersion('git', 'v');

        // Overwrite archive format, name and version if given
        $arguments = $e->getArguments();

        if (isset($arguments[0])) {
            $archiveFormat = $arguments[0];
        }

        if (isset($arguments[1])) {
            $archiveName = $arguments[1];
        }

        if (isset($arguments[2])) {
            $archiveVersion = $arguments[2];
        }

        $archiveFileName = "$archiveName-$archiveVersion";

        $fullArchivePath = realpath($archivePath);

        switch ($archiveFormat) {
            case "zip":
                echo `cd {$fullArchivePath};zip -r -q -9 $archiveFileName.zip $archiveFileName/`;
                break;
            case "exe":
                echo `cd {$fullArchivePath};zip -r -q -9 $archiveFileName.zip $archiveFileName/`;
                `cd $archivePath; wget ftp://ftp.info-zip.org/pub/infozip/win32/unz552xn.exe`;
                `cd $archivePath; cat unz552xn.exe $archiveFileName.zip > $archiveFileName.exe`;
                break;

            case "tar.gz":
            default:
                echo `cd {$fullArchivePath};tar c $archiveFileName/ | gzip --best > $archiveFileName.tar.gz`;
        }
        echo `du -h "{$fullArchivePath}/$archiveFileName.$archiveFormat"`;
    }
}
