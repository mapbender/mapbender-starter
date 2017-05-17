<?php
use Composer\Script\Event;

/**
 * Class ScriptHandler
 */
class ComposerBootstrap
{
    /**
     * Builds the bootstrap file.
     *
     * The bootstrap file contains PHP file that are always needed by the application.
     * It speeds up the application bootstrapping.
     *
     * @param $event Event A instance
     */
    public static function checkConfiguration($event)
    {
        $files        = static::getDefaultParameterFiles();
        $isNewInstall = !file_exists($files["current"]);
        //$configuration         = file_get_contents($configurationBaseFile);

        if ($isNewInstall) {

            copy($files["default"], $files["current"]);

            static::createDatabase();
            static::resetRootLogin();
            static::importExampleApplications();
            static::updateEpsgCodes();
        }

        static::clearCache();
    }

    /**
     * Rebuild database.
     * Needs for tests
     *
     * @param $event
     */
    public static function rebuildDatabase($event)
    {
        $files        = static::getDefaultParameterFiles();
        $isNewInstall = !file_exists($files["current"]);

        if ($isNewInstall) {
            copy($files["default"], $files["current"]);
        }

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
        $isWindows = static::isWindows();
        $cachePath = "app/cache";
        if (!$isWindows) {
            static::printStatus("Clear cache");
            foreach (glob($cachePath . "/*/*") as $filePath) {
                $fileInfo = explode("/", $filePath);
                $fileName = end($fileInfo);
                if ($fileName == "sessions") {
                    continue;
                }
                //$filePath = escapeshellarg($filePath);
                echo `rm -rf $filePath`;
            }
        }
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony app/console assets:install).
     */
    public static function installAssets()
    {
        $files        = static::getDefaultParameterFiles();
        $isNewInstall = !file_exists($files["current"]);

        if ($isNewInstall) {
            return;
        }

        $isWindows = static::isWindows();
        if ($isWindows) {
            static::installHardCopyAssets();
        } else {
            static::installSymLinkAssets();
        }
    }

    /**
     * Set binaries executable permission
     */
    public static function prepareBinaries()
    {
        if (static::isWindows()) {
            return;
        }

        echo `chmod +x vendor/eslider/sasscb/dist/sassc`;
        echo `chmod +x vendor/eslider/sasscb/dist/sassc.x86`;
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony app/console assets:install).
     */
    public static function installHardCopyAssets()
    {
        echo `php app/console assets:install web`;
    }

    /**
     * Installs bundle assets into a given dierectory (for details s. Symfony app/console assets:install).
     */
    public static function installSymLinkAssets()
    {
        echo `php app/console assets:install web --symlink --relative `;
    }

    /**
     * Enable write cache, logs and upload folder by user and group
     */
    public static function allowWriteLogs()
    {
        if (!static::isWindows()) {
            static::printStatus("Enable write cache, logs and upload for user and user group");

            echo `chmod -R ug+wX app/cache`;
            echo `chmod -R ug+wX app/logs`;
            echo `chmod -R ug+wX web/uploads`;
        }
    }

    /**
     * Crate database, schema and force update
     */
    public static function dropDatabase()
    {
        static::printStatus("Drop database");

        echo `php app/console doctrine:database:drop --force`;
    }

    /**
     * Crate database, schema and force update
     */
    public static function createDatabase()
    {
        static::printStatus("Create and prepare database");

        echo `php app/console doctrine:database:create`;
        echo `php app/console doctrine:schema:create`;
        //echo `php app/console doctrine:schema:update --force`;
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
        $userName  = escapeshellarg($userName);
        $userEmail = escapeshellarg($userEmail);
        $password  = escapeshellarg($password);

        static::printStatus("Reset user password");

        `php app/console fom:user:resetroot --username $userName --password $password --email $userEmail --silent`;

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
        echo `php app/console doctrine:fixtures:load --fixtures=mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Epsg/ --append`;
    }

    /**
     * Import example applications from "app/config/mapbender.yml" file
     */
    public static function importExampleApplications()
    {
        static::printStatus("Import example mapbender applications");
        echo `php app/console doctrine:fixtures:load --fixtures=mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Application/ --append`;
    }

    /**
     * @return string
     */
    protected static function printStatus($title)
    {
        echo "\n[$title]\n";
    }

    /**
     * Generate API documentation
     */
    public static function genApiDocumentation()
    {
        if (!is_file("bin/apigen")) {
            return;
        }

        $parameters     = \Symfony\Component\Yaml\Yaml::parse(file_get_contents("app/config/parameters.yml"));
        $version        = $parameters["parameters"]["fom"]["server_version"];
        $title          = escapeshellarg("Mapbender " . $version . " API documenation");
        $configFilePath = "../apigen.conf";
        $config         = parse_ini_file($configFilePath);
        static::printStatus("Generate Mapbender {$version} API documenation to '{$config['destination']}'");
        echo `bin/apigen -c $configFilePath --title $title`;
    }

    public static function genDocumentation()
    {
        if (static::isWindows()) {
            return;
        };
        $sphinxPath = preg_replace("/^.* |\\s*$/s", "", `type sphinx-build`);
        if (strpos($sphinxPath, "sphinx-build") !== false) {
            `$sphinxPath vendor/mapbender/documentation web/docs`;
        } else {
            echo "Documentation isn't generated, please install python sphinx documentation generator.";
        }
    }

    /**
     * Display version
     */
    public static function displayVersion()
    {
        $composerDef = static::getComposerDefinition();
        echo $composerDef["version"] . "\n";
    }

    /**
     * @return array Definition
     */
    public static function getComposerDefinition()
    {
        static $config;
        if (!$config) {
            $config = json_decode(file_get_contents(static::getRootPath() . "/application/composer.json"), true);
        }
        return $config;
    }

    /**
     * @return string
     */
    protected static function getRootPath()
    {
        return realpath(__DIR__ . "/../../");
    }

    /**
     * @return string
     * @internal param $rootPath
     */
    protected static function getConfigPath()
    {
        return static::getSymfonyRootPath() . "/app/config/";
    }

    /**
     * @return array
     */
    protected static function getDefaultParameterFiles()
    {
        $path = static::getConfigPath();
        return array(
            "default" => $path . "parameters.yml.dist",
            "current" => $path . "parameters.yml");
    }

    /**
     * @return int
     * @internal param $composerDef
     */
    private static function updateSymfonyParameters()
    {
        $files = static::getDefaultParameterFiles();
        static::updateYamlServerDescription($files["default"]);
        if (is_file($files["current"])) {
            static::updateYamlServerDescription($files["current"]);
        }
    }

    /**
     * @param $yamlFile
     * @return int
     * @internal param $composerDef
     */
    private static function updateYamlServerDescription($yamlFile)
    {
        $composerDef = static::getComposerDefinition();
        $yamlContent = preg_replace(
            "/(fom:\\s+server_name:)(\\s*\\S+)(\\s+server_version:)(\\s*\\S+)/s",
            "$1 " . $composerDef["description"] .
            "$3 " . $composerDef["version"],
            file_get_contents($yamlFile));
        return file_put_contents(
            $yamlContent,
            $yamlFile);
    }

    /**
     * @return int
     */
    protected static function updatePhingProperties()
    {
        $initFile       = array();
        $config         = static::getComposerDefinition();
        $versionDetails = sscanf($config["version"], "%d.%d.%d.%d-%s");
        $properties     = static::getRootPath() . "/build.properties";
        $build          = array_merge(parse_ini_file($properties), array(
            'version.major'            => $versionDetails[0],
            'version.minor'            => $versionDetails[1],
            'version.revision'         => $versionDetails[2],
            'version.build'            => $versionDetails[3],
            'packaging.release'        => $versionDetails[4] ? $versionDetails[4] : 0,
            'phing.project.name'       => $config["name"],
            'project.shortdescription' => $config["description"],
            'project.description'      => $config["description"] . " (" . $config["homepage"] . ")",
        ));

        // Build INI content
        foreach ($build as $k => $v) {
            $initFile[] = $k . "=\"" . $v . "\"";
        }

        return file_put_contents($properties, implode("\n", $initFile));
    }

    /**
     * @param Event $e
     */
    public static function distribute($e)
    {
        /** @var \Composer\Package\RootPackage $package */
        $package         = $e->getComposer()->getPackage();
        $config          = $package->getConfig();
        $archiveName     = current(array_slice(explode("/", $package->getName()), -1));
        $archiveFormat   = $config["archive-format"];
        $archivePath     = $config["archive-dir"];
        $archiveVersion  = $package->getVersion();

        // Overwrite archive format, name and version if given
        $arguments = $e->getArguments();

        if(isset($arguments[0]) ){
            $archiveFormat = $arguments[0];
        }

        if(isset($arguments[1]) ){
            $archiveName = $arguments[1];
        }

        if(isset($arguments[2]) ){
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

        $fullArchivePath    = realpath($archivePath);
        $archiveProjectPath = "$fullArchivePath/$archiveFileName";
        $assetsPath         = $archiveProjectPath . "/web/bundles";

        // Remove assets
        if(is_dir($assetsPath)){
            echo `rm -rf "${assetsPath}"`;
        }
        // Copy project files
        echo `cp -rf . $archiveProjectPath`;

        // Remove development files
        echo `find $archiveProjectPath -name "*.git*" | xargs rm -rf `;
        echo `find $archiveProjectPath -name "*.travis.yml" | xargs rm -rf `;
        echo `find $archiveProjectPath/vendor -type d -iname "tests" | xargs rm -rf `;
        echo `find $archiveProjectPath/vendor -type d -iname "demo" | xargs rm -rf `;

        foreach (array(
                     "documentation",
                     "apidoc",
                     "dist",
                     "vendor/mapbender/documentation",
                     "vendor/mapbender/mapbender-icons",

                     "vendor/mnsami/composer-custom-directory-installer",
                     "vendor/robloach/component-installer",

                     "vendor/phing",
                     "vendor/apigen",
                     "vendor/phpunit",
                     "vendor/phantomjs",
                     "vendor/predis",
                     "vendor/satooshi/php-coveralls",
                     "vendor/facebook/webdriver",
                     "vendor/fabpot/sphinx-php",

                     "vendor/afarkas",
                     "vendor/debugteam",
                     "vendor/components",
                     "vendor/medialize/jquery-context-menu",
                     "vendor/rogeriopradoj/respond",
                     "vendor/afarkas/html5shiv",
                     "vendor/fontfacekit/open-sans",

                     "web/app_test.php",
                     "web/index.php",

                     "app/cache/*",
                     "app/logs/*",


                 ) as $path) {
            echo `rm -rf $archiveProjectPath/{$path}`;
        }

        // Copy license and readme files
        echo `find vendor/ -type f -iname "license*" | xargs -i cp --parents "{}" "${archiveProjectPath}"`;
        echo `find vendor/ -type f -iname "readme*" | xargs -i cp --parents "{}" "${archiveProjectPath}"`;

        // Copy project info files
        echo `cp ../LICENSE "${archiveProjectPath}/"`;
        echo `cp ../README.md "${archiveProjectPath}/"`;
        echo `cp ../CONTRIBUTING.md "${archiveProjectPath}/"`;
        echo `cp ../CHANGELOG.md "${archiveProjectPath}/"`;

        echo "Distributed to: $archiveProjectPath\n";
    }

    /**
     * @param Event $e
     * @return int
     */
    public static function build($e)
    {
        /** @var \Composer\Package\RootPackage $package */
        $package         = $e->getComposer()->getPackage();
        $config          = $package->getConfig();
        $archiveName     = current(array_slice(explode("/", $package->getName()), -1));
        $archiveFormat   = $config["archive-format"];
        $archivePath     = $config["archive-dir"];
        $archiveVersion  = $package->getVersion();

        // Overwrite archive format, name and version if given
        $arguments = $e->getArguments();

        if(isset($arguments[0]) ){
            $archiveFormat = $arguments[0];
        }

        if(isset($arguments[1]) ){
            $archiveName = $arguments[1];
        }

        if(isset($arguments[2]) ){
            $archiveVersion = $arguments[2];
        }

        $archiveFileName = "$archiveName-$archiveVersion";

        $fullArchivePath = realpath($archivePath);

        switch ($archiveFormat) {
            case "zip":
                echo `cd {$fullArchivePath};zip -r -q -9 ${archiveFileName}.zip $archiveFileName/`;
                break;
            case "exe":
                echo `cd {$fullArchivePath};zip -r -q -9 ${archiveFileName}.zip $archiveFileName/`;
                `cd $archivePath; wget ftp://ftp.info-zip.org/pub/infozip/win32/unz552xn.exe`;
                `cd $archivePath; cat unz552xn.exe ${archiveFileName}.zip > ${archiveFileName}.exe`;
                break;

            case "tar.gz":
            default:
                echo `cd {$fullArchivePath};tar c $archiveFileName/ | gzip --best > ${archiveFileName}.tar.gz`;
        }
        echo `du -h "{$fullArchivePath}/${archiveFileName}.${archiveFormat}"`;
    }

    /**
     * Generate change log
     */
    public static function generateChangeLog()
    {
        $logs        = array();
        $logFileName = "./CHANGELOG";
        foreach (array("Mapbender-Starter" => '../',
                       "Mapbender"         => 'mapbender',
                       "FOM"               => 'fom',
                       "OwsProxy3"         => 'owsproxy') as $repoName => $repoPath) {
            $rawLog = `git --git-dir "${repoPath}/.git" log --tags --pretty=format:"%s" `; //--date=short -pretty=format:"%ad: %s"

            $logs[]  = "\n# " . $repoName . "\n";
            $logList = explode("\n", $rawLog);

            foreach ($logList as $i => $logMessage) {
                $logMessage = trim($logMessage);
                $logMessage = preg_replace("/^fix /is", "Fixed ", $logMessage);
                $logMessage = preg_replace("/^merge /is", "Merged ", $logMessage);
                $logMessage = preg_replace("/^bump /is", "Bumped ", $logMessage);
                $logMessage = preg_replace("/^include /is", "Included  ", $logMessage);
                $logMessage = preg_replace("/^add /is", "Added ", $logMessage);
                $logMessage = preg_replace("/^Optimize /is", "Optimized ", $logMessage);
                $logMessage = preg_replace("/^Translate /is", "Translated ", $logMessage);
                $logMessage = preg_replace("/^Refactor /is", "Refactored ", $logMessage);
                $logMessage = preg_replace("/^Improve /is", "Improved ", $logMessage);

                if (
                    $logMessage == "Fom update"
                    || $logMessage == "Mapbender update"
                    || $logMessage == "Bumped Mapbender"
                    || $logMessage == "Bumped Fom"
                ) {
                    continue;

                }
                $logList[ $i ] = " * " . ucfirst($logMessage);
            }


            $logs    = array_merge($logs, array_unique($logList));
        }
        file_put_contents($logFileName, implode("\n", $logs));
        return $logFileName;
    }
}
