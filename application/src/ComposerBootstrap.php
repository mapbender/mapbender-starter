<?php
use Composer\Script\Event;

/**
 * Class ScriptHandler
 */
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
        passthru("php app/console doctrine:schema:create", $status);
        if ($status === 0) {
            `php app/console mapbender:database:init -v`;
            static::resetRootLogin();
        }
    }

    public static function reimportExampleApps()
    {
        static::importExampleApplications();
        static::clearCache();
    }

    /**
     * @return bool true if config file needed to be created
     */
    protected static function ensureConfig()
    {
        $configPath = static::getParametersPath();

        if (!file_exists($configPath)) {
            copy("{$configPath}.dist", $configPath);
            return true;
        } else {
            return false;
        }
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
        echo `php app/console mapbender:database:init -v`;
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
                fwrite(STDERR, "WARNING: Passsed version prefix " . print_r($stripPrefix, true) . " does not occur in version {$version}\n");
                return $version;
            }
        }
        switch ($mode) {
            default:
                throw new \InvalidArgumentException("Unsupported mode " . print_r($mode, true));
            case 'git':
                return trim(@shell_exec('git describe --tags'));
            case 'composer':
                fwrite(STDERR, "WARNING: 'composer' version mode is deprecated, please update your scripts to 'git' mode\n");
                return trim(@shell_exec('git describe --tags --abbrev=0'));
        }
    }

    /**
     * Display version
     *
     * @param Event $e
     */
    public static function displayVersion(Event $e)
    {
        $args = $e->getArguments();
        if (array_intersect(array('--help', '-h', 'help'), $args)) {
            echo implode("\n", array(
                "Usage:",
                "   bin/composer run version composer",
                "       Displays root package version as defined in composer.json",
                "   bin/composer run git-current [tag-prefix]",
                "       Prints the latest revision tag. Falls back to '0.0.0.0' if the repository is not"
                . " yet tagged at all.",
                "   bin/composer run version git-next [tag-prefix]",
                "       Prints a proposed next, unused tag.",
                "   bin/composer run version git [tag-prefix]",
                "       Deprecated alias for 'git-next'.",
                "",
                "Both 'git-current' and 'git-next' inspect current branch name (if it looks like a version)"
                - " and / or tags starting with the optional <tag-prefix> when looking for minor"
                . " versions.",
                "",
                "The printed tag DOES NOT start with <tag-prefix>. If you want the prefix, you have to"
                . " re-add it yourself. This is useful if you want to 'mirror' tags from Github in your"
                . " fork, using a different prefix.",
                "E.g. you can transpose the current Github tag, assuming v3.0.7.4, into a gh3.0.7.4 with (bash):",
                "   _MIRROR_TAG=\"gh\"\"$(bin/composer run version git-current v)\"",
                "",
                "",
            ));
            exit(0);
        }
        $defaults = array('composer', '');
        if (count($args) >= 2 && ($args[1] == 'next-revision' || $args[1] == 'minor')) {
            fwrite(STDERR, "WARNING: second argument {$args[1]} is redundant, please update your scripts\n");
            $args = array_merge(array($args[0]), array_slice($args, 2));
        }
        list($mode, $tagPrefix) = array_replace($defaults, $args);
        switch ($mode) {
            case 'composer':
                if (count($args) > 1) {
                    throw new \InvalidArgumentException("Extra arguments not supported for 'composer' mode");
                }
                echo static::getVersion('composer', 'v') . "\n";
                exit(0);
            case 'git':
            case 'git-next':
            case 'git-current':
                $branch = self::getGitBranchName();
                $branchNameParts = explode("/", $branch, 2);

                // Grab minor version from most recent git tag
                // most recent tag name on current branch with no decoration
                $currentTag = trim(@shell_exec('git describe --tags --abbrev=0 ' . escapeshellarg($branch))) ?: null;
                if (!$currentTag) {
                    fwrite(STDERR, "WARNING: git describe failed, on {$branch}, trying again on origin/{$branch}\n");
                    $currentTag = trim(@shell_exec('git describe --tags --abbrev=0 ' . escapeshellarg("origin/{$branch}"))) ?: null;
                }
                if ($currentTag) {
                    // remove 'tagPrefix' prefix
                    $minorGitVersion = substr($currentTag, strlen($tagPrefix));
                } else {
                    $minorGitVersion = null;
                }
                $minorVersionPattern = '/^[\d]+(\.(RC-)?\d+)*$/';
                if (count($branchNameParts) > 1 && preg_match($minorVersionPattern, $branchNameParts[1])) {
                    // this only works for branches named 'release/3.0.4', 'kakadu-project/27.4.11' etc
                    $projectMinorVersion = $branchNameParts[1];
                } else if ($minorGitVersion) {
                    // remove last number, possible 'RC-' prefix
                    $projectMinorVersion = preg_replace('#[-.]\d+((-?RC-?)?\d+)?$#', '', $minorGitVersion);
                } else {
                    $fallback = '0.0.0';
                    fwrite(STDERR, "WARNING: Can't extract minor version from neither branch name or current commit. Using {$fallback}.\n");
                    $projectMinorVersion = $fallback;
                }

                $notProjectNames = array(
                    'release',
                    'master',
                    'develop',
                    'fix',
                    'feature',
                    'hotfix',
                    'enh',
                    'enhancement',
                );
                // Match tags that start with
                // 1) the global tag prefix (from command line)
                // 2) the branch base name (unless blacklisted)
                // 3) the calculated minor version
                $matchTagPrefix = $tagPrefix;
                if (count($branchNameParts) > 1 && !in_array($branchNameParts[0], $notProjectNames)) {
                    $matchTagPrefix .= $branchNameParts[0];
                }
                $matchTagPrefix .= $projectMinorVersion;
                $escapedMatchTagPattern = escapeshellarg("{$matchTagPrefix}*");
                $matchingTags = array_filter(explode("\n", trim(`git tag -l -- $escapedMatchTagPattern`)));
                if ($matchingTags) {
                    // extract only the final group of consecutive digits as "revisions"
                    $revisions = preg_filter('#(^.{' . (strlen($matchTagPrefix) + 1) . '})(\S+)#sm', '$2', $matchingTags);
                } else {
                    $revisions = array();
                }

                natsort($revisions);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported argument 1 " . var_export($mode, true));
        }
        switch ($mode) {
            case 'git':         // legacy alias
            case 'git-next':
                $nextRevision = 0;
                foreach (array_reverse($revisions) as $revision) {
                    if (false === strpos($revision, 'RC')) {
                        $nextRevision = intval($revision) + 1;
                        break;
                    } else {
                        $nonRcRevision = intval($revision);
                        if (!in_array($nonRcRevision, $revisions)) {
                            // we have an RC version as the highest, and there is no corresponding non-RC
                            // => "upgrade" the RC patch level directly to a non-RC patch level
                            $nextRevision = $nonRcRevision;
                            break;
                        }
                    }
                }
                echo "{$projectMinorVersion}.{$nextRevision}\n";
                exit(0);
            case 'git-current':
                if ($revisions) {
                    $currentPatchLevel = max($revisions);
                    if (false !== strpos($currentPatchLevel, 'RC')) {
                        $nonRcRevision = intval($currentPatchLevel);
                        if (in_array($nonRcRevision, $revisions)) {
                            // We had an RC version as the highest, but found the corresponding non-RC
                            // => the non-RC is the highest available version
                            $currentPatchLevel = $nonRcRevision;
                        }
                    }
                } else {
                    $currentPatchLevel = 0;
                }
                echo "{$projectMinorVersion}.{$currentPatchLevel}\n";
                exit(0);
        }
    }

    /**
     * Display name
     *
     * @param Event $e
     */
    public static function displayProjectName($e)
    {
        $defaults = array('git');

        list($vendorType) = array_replace($defaults, $e->getArguments());

        if ($vendorType == "composer") {
            /** @var \Composer\Package\RootPackage $rootPackage */
            $rootPackage = $e->getComposer()->getPackage();
            $projectName = current(array_slice(explode('/', $rootPackage->getName()), -1));
            echo $projectName . "\n";
            return;
        }

        if ($vendorType == "git") {
            $branchNameParts = explode('/', self::getGitBranchName());
            $firstPart = implode('', array_slice($branchNameParts, 0, 1));
            echo "{$firstPart}\n";
        }
    }

    /**
     * @return string
     */
    protected static function getParametersPath()
    {
        return static::getSymfonyRootPath() . '/app/config/parameters.yml';
    }

    /**
     * @return string
     */
    public static function getGitBranchName()
    {
        $branchName = trim(`git rev-parse --abbrev-ref HEAD`);
        if ($branchName == "HEAD") {
            $branchName = trim(`echo \$CI_COMMIT_REF_NAME`);
        }
        return $branchName;
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
        $archiveVersion = static::getVersion('git', 'v');

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
                     "vendor/mapbender/documentation",

                     "vendor/mnsami/composer-custom-directory-installer",
                     "vendor/robloach/component-installer",

                     "vendor/phing",
                     "vendor/phpunit",
                     "vendor/phantomjs",
                     "vendor/predis",
                     "vendor/satooshi/php-coveralls",
                     "vendor/fabpot/sphinx-php",

                     "web/app_test.php",
                     "web/index.php",

                     "app/cache/*",
                     "app/logs/*",
                 ) as $path) {
            echo `rm -rf $archiveProjectPath/{$path}`;
        }

        // Copy license and readme files
        echo `find vendor/ -type f -iname "license*" | xargs -I'{}' cp --parents '{}' "${archiveProjectPath}"`;
        echo `find vendor/ -type f -iname "readme*" | xargs -I'{}' cp --parents '{}' "${archiveProjectPath}"`;

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
        $archiveVersion = static::getVersion('git', 'v');

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
}
