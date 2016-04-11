<?php
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

/**
 * Class ScriptHandler
 */
class ScriptHandler
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
        $rootPath              = realpath(__DIR__ . "/..");
        $configPath            = $rootPath . "/app/config/";
        $configurationBaseFile = $configPath . "parameters.yml.dist";
        $configurationFile     = $configPath . "parameters.yml";
        $isNewInstall          = !file_exists($configurationFile);
        $isWindows             = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        //$configuration         = file_get_contents($configurationBaseFile);

        if ($isNewInstall) {
            $hasApplicationSubFolder = is_dir($rootPath . "/../.git");

            if ($hasApplicationSubFolder) {
                echo `cd ..`;
            }

            echo `git submodule sync`;
            echo `git submodule update --init --recursive`;

            if ($hasApplicationSubFolder) {
                echo `cd "$rootPath"`;
            }

            copy($configurationBaseFile, $configurationFile);

            echo `php app/console doctrine:database:create`;
            echo `php app/console doctrine:schema:create`;
            echo `php app/console app/console doctrine:schema:update --force`;
            echo `php app/console fom:user:resetroot --username root --password root --email root@localhost --silent`;
            echo `php app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Epsg/ --append`;
            echo `php app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Application/ --append`;
        }



        if (!$isWindows) {
            echo `rm -rf app/cache/*`;
            echo `chmod -R ug+w app/cache`;
            echo `chmod -R ug+w app/logs`;
            echo `chmod -R ug+w web/uploads`;
        }
    }
}
