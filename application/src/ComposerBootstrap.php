<?php
use Composer\Script\CommandEvent;

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
     * @param $event CommandEvent A instance
     */
    public static function checkConfiguration(CommandEvent $event)
    {
        $files        = static::getDefaultParameterFiles();
        $isNewInstall = !file_exists($files["current"]);
        //$configuration         = file_get_contents($configurationBaseFile);

        if ($isNewInstall) {

            static::updateSubmodules();

            copy($files["default"], $files["current"]);

            static::createDatabase();
            static::resetRootLogin();
            static::importExampleApplications();
            static::updateEpsgCodes();
        }

        static::clearCache();
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
        $isWindows = static::isWindows();
        if (!$isWindows) {
            echo `php app/console assets:install --symlink --relative web`;
        } else {
            echo `php app/console assets:install web`;
        }
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
     * Update submodules
     */
    public static function updateSubmodules()
    {
        $rootPath                = static::getSymfonyRootPath();
        $hasApplicationSubFolder = is_dir($rootPath . "/../.git");
        if ($hasApplicationSubFolder) {
            echo `cd ..`;
        }

        static::printStatus("Update submodules");

        echo `git submodule sync`;
        echo `git submodule update --init --recursive`;

        if ($hasApplicationSubFolder) {
            echo `cd "$rootPath"`;
        }
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

    public static function release()
    {
        static::updatePhingProperties();
        static::updateSymfonyParameters();
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
        var_dump($yamlContent);
        die();
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
}
