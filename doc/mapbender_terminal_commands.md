# Mapbender terminal commands
Documentation of the Mapbender terminal commands needed for quick installation and updating by using composer.json.

Since Mapbender comes with the [composer tool](https://getcomposer.org/) as a dependeny manager innately, you can do all composer editing from the console as well. The following commands are a selection that can be done via the console.

For all available commands and their description take a look at the [doc page](https://getcomposer.org/doc/03-cli.md).

# Install dependencies

The `install` command reads the composer.json file from the current Mapbender directory, resolves the dependencies, and installs them in the vendor directory. The install command is used by Mapbender `bootstrap`, see [Link](https://github.com/mapbender/mapbender-starter/blob/release/3.0.7/bootstrap).

```bash
php composer.phar install
```
If a composer.lock file exists in the current Mapbender directory, it uses the exact versions from the lock file instead of resolving them. The lock file ensures that anyone using the MB library receives and uses the same versions of the dependencies.

If there is no composer.lock file, composer creates its own lock file but with the version from the reprocessors.

# Update dependencies

The command `update` reads the file composer.json from the current directory, where it processes, updates, removes or installs all dependencies.

```bash
php composer.phar update
```

#### Update with optimized order

Optimize the autoloader during the autoloader dump with this extra command:

```bash
php composer.phar update -o
```

* example:

```text
/application$: php composer.phar update -o
Loading composer repositories with package information
Updating dependencies (including require-dev)
Nothing to install or update
Package components/datatables is abandoned, you should avoid using it. Use bower-asset/datatables instead.
Package guzzle/guzzle is abandoned, you should avoid using it. Use guzzlehttp/guzzle instead.
Generating optimized autoload files
> ComponentInstaller\Installer::postAutoloadDump
Compiling component files
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::buildBootstrap
> ComposerBootstrap::installAssets

 Trying to install assets as relative symbolic links.

 --- -------------------------- ------------------ 
      Bundle                     Method / Error    
 --- -------------------------- ------------------ 
  ✔   FrameworkBundle            relative symlink  
  ✔   FOSJsRoutingBundle         relative symlink  
  ✔   FOMCoreBundle              relative symlink  
  ✔   FOMManagerBundle           relative symlink  
  ✔   FOMUserBundle              relative symlink  
  ✔   MapbenderCoreBundle        relative symlink  
  ✔   MapbenderWmcBundle         relative symlink  
  ✔   MapbenderWmsBundle         relative symlink  
  ✔   MapbenderManagerBundle     relative symlink  
  ✔   MapbenderPrintBundle       relative symlink  
  ✔   MapbenderMobileBundle      relative symlink  
  ✔   MapbenderDigitizerBundle   relative symlink  
  ✔   SensioDistributionBundle   relative symlink  
 --- -------------------------- ------------------ 

 [OK] All assets were successfully installed.                                                                           

> ComposerBootstrap::prepareBinaries
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::installRequirementsFile
```

#### Update individual or specific packages

To limit the upgrade process to a few packages you can specify the packages to update like shown below:
 
```bash
php composer.phar update mapbender/mapbender mapbender/data-source
```

* example:

```text
/application$: php composer.phar update mapbender/mapbender mapbender/data-source
Loading composer repositories with package information                                                                                         Updating dependencies (including require-dev)         Package operations: 0 installs, 1 update, 0 removals
  - Updating mapbender/mapbender dev-release/3.0.6 (9fde835 => ee7a6bc):  Checking out ee7a6bc1fc
Package components/datatables is abandoned, you should avoid using it. Use bower-asset/datatables instead.
Package guzzle/guzzle is abandoned, you should avoid using it. Use guzzlehttp/guzzle instead.
Writing lock file
Generating optimized autoload files
> ComponentInstaller\Installer::postAutoloadDump
Compiling component files
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::buildBootstrap
> ComposerBootstrap::installAssets

 Trying to install assets as relative symbolic links.

 --- ----------------------------------- ------------------ 
      Bundle                              Method / Error    
 --- ----------------------------------- ------------------ 
  ✔   FrameworkBundle                     relative symlink  
  ✔   FOSJsRoutingBundle                  relative symlink  
  ✔   FOMCoreBundle                       relative symlink  
  ✔   FOMManagerBundle                    relative symlink  
  ✔   FOMUserBundle                       relative symlink  
  ✔   MapbenderCoreBundle                 relative symlink  
  ✔   MapbenderWmcBundle                  relative symlink  
  ✔   MapbenderWmsBundle                  relative symlink  
  ✔   MapbenderManagerBundle              relative symlink  
  ✔   MapbenderPrintBundle                relative symlink  
  ✔   MapbenderMobileBundle               relative symlink  
  ✔   MapbenderDigitizerBundle            relative symlink  
  ✔   MapbenderRoutingBundle              relative symlink  
  ✔   MapbenderCoordinatesUtilityBundle   relative symlink  
  ✔   SensioDistributionBundle            relative symlink  
 --- ----------------------------------- ------------------ 

 [OK] All assets were successfully installed.                                                                           

> ComposerBootstrap::prepareBinaries
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::installRequirementsFile

```

#### Update prod dependencies only

If only the non-dev/production packages shall be updated, then you can optionally specify the parameter `--no-dev`. It disables the installation of require-dev packages.

```bash
php composer.phar update --no-dev
```

* example:

```text
/application$: php composer.phar update --no-dev
              Loading composer repositories with package information
              Updating dependencies
              Nothing to install or update
              Package operations: 0 installs, 0 updates, 31 removals
                - Removing webmozart/assert (1.2.0)
                - Removing texy/texy (v2.4)
                - Removing sebastian/version (1.0.6)
                - Removing sebastian/recursion-context (1.0.5)
                - Removing sebastian/global-state (1.1.1)
                - Removing sebastian/exporter (1.2.2)
                - Removing sebastian/environment (1.3.8)
                - Removing sebastian/diff (1.4.3)
                - Removing sebastian/comparator (1.2.4)
                - Removing satooshi/php-coveralls (v0.7.1)
                - Removing predis/predis (v1.1.1)
                - Removing phpunit/phpunit-selenium (2.0.3)
                - Removing phpunit/phpunit-mock-objects (2.3.8)
                - Removing phpunit/phpunit (4.8.36)
                - Removing phpunit/php-token-stream (1.4.12)
                - Removing phpunit/php-timer (1.0.9)
                - Removing phpunit/php-text-template (1.2.1)
                - Removing phpunit/php-file-iterator (1.4.5)
                - Removing phpunit/php-code-coverage (2.2.4)
                - Removing phpspec/prophecy (1.7.3)
                - Removing phpdocumentor/type-resolver (0.3.0)
                - Removing phpdocumentor/reflection-docblock (3.2.2)
                - Removing phpdocumentor/reflection-common (1.0.1)
                - Removing phing/phing (2.15.2)
                - Removing phantomjs/phantomjs (1.8.2)
                - Removing nette/nette (v2.1.12)
                - Removing kukulich/fshl (2.1.0)
                - Removing guzzle/guzzle (v3.9.3)
                - Removing facebook/webdriver (1.1.3)
                - Removing apigen/apigen (v2.8.1)
                - Removing andrewsville/php-token-reflection (1.3.1)
              Package components/datatables is abandoned, you should avoid using it. Use bower-asset/datatables instead.
              Generating optimized autoload files
              > ComponentInstaller\Installer::postAutoloadDump
              Compiling component files
              > Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::buildBootstrap
              > ComposerBootstrap::installAssets
              
               Trying to install assets as relative symbolic links.
              
               --- -------------------------- ------------------ 
                    Bundle                     Method / Error    
               --- -------------------------- ------------------ 
                ✔   FrameworkBundle            relative symlink  
                ✔   FOSJsRoutingBundle         relative symlink  
                ✔   FOMCoreBundle              relative symlink  
                ✔   FOMManagerBundle           relative symlink  
                ✔   FOMUserBundle              relative symlink  
                ✔   MapbenderCoreBundle        relative symlink  
                ✔   MapbenderWmcBundle         relative symlink  
                ✔   MapbenderWmsBundle         relative symlink  
                ✔   MapbenderManagerBundle     relative symlink  
                ✔   MapbenderPrintBundle       relative symlink  
                ✔   MapbenderMobileBundle      relative symlink  
                ✔   MapbenderDigitizerBundle   relative symlink  
                ✔   SensioDistributionBundle   relative symlink  
               --- -------------------------- ------------------ 
              
               [OK] All assets were successfully installed.                                                                           
              
              > ComposerBootstrap::prepareBinaries
              > Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::installRequirementsFile
```

# Exporting Mapbender

It is possible to create a filesystem copy of your current mapbender project with the ```build``` command. The result will be saved in the `dist /` directory of the project.

#### Building a copy

```bash
php composer.phar build

```

#### Creating a build/ZIP/Tar.gz

In addition, a ZIP or Tar.gz file can be created for export.

```bash
php composer.phar build zip 

php composer.phar build tar.gz
```

* example:

```text
application$: php composer.phar build tar.gz
> ComposerBootstrap::distribute

 Trying to install assets as relative symbolic links.

 --- -------------------------- ------------------ 
      Bundle                     Method / Error    
 --- -------------------------- ------------------ 
  ✔   FrameworkBundle            relative symlink  
  ✔   FOSJsRoutingBundle         relative symlink  
  ✔   FOMCoreBundle              relative symlink  
  ✔   FOMManagerBundle           relative symlink  
  ✔   FOMUserBundle              relative symlink  
  ✔   MapbenderCoreBundle        relative symlink  
  ✔   MapbenderWmcBundle         relative symlink  
  ✔   MapbenderWmsBundle         relative symlink  
  ✔   MapbenderManagerBundle     relative symlink  
  ✔   MapbenderPrintBundle       relative symlink  
  ✔   MapbenderMobileBundle      relative symlink  
  ✔   MapbenderDigitizerBundle   relative symlink  
  ✔   SensioDistributionBundle   relative symlink  
 --- -------------------------- ------------------ 

 [OK] All assets were successfully installed.                                                                           

Distributed to: /home/XX/Projekte/Mapbender/mapbender-starter-commands/dist/mapbender3-starter-3.0.6.3
> ComposerBootstrap::build
27M     /home/XX/Projekte/Mapbender/mapbender-starter-commands/dist/mapbender3-starter-3.0.6.3.tar.gz
```
