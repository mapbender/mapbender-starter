## dev-master @ d0c1507
- Updated mapbender/digitizer to [1.4.7](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.4.7)
- Updated mapbender/data-manager to [1.1.9](https://github.com/mapbender/data-manager/releases/tag/1.1.9)
- Updated mapbender/owsproxy to [v3.1.7](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.7)
- Add ShareUrl element to demo applications (see [Mapbender PR#1328](https://github.com/mapbender/mapbender/pull/1328))
- Add ApplicationSwitcher element to demo applications (see [Mapbender PR#1307](https://github.com/mapbender/mapbender/pull/1307))
- Update demo application titles and descriptions
- Fix WmsLoader button tooltips in demo applications
- Add firewall exclusion for Controller delivery of components (see [Mapbender PR#1352](https://github.com/mapbender/mapbender/pull/1352))
- Disabled ResetView element (funcionality integrated into ZoomBar "zoom_home" component)
- Disabled DataManager element (installed as Digitizer dependency; not usually relevant on its own)

## v3.2.4
- Updated mapbender/mapbender to [v3.2.4](https://github.com/mapbender/mapbender/releases/tag/v3.2.4)
- Updated mapbender/digitizer to [1.4.5](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.4.5)
- Updated mapbender/data-manager to [1.1.6](https://github.com/mapbender/data-manager/releases/tag/1.1.6)
- Updated symfony/symfony to [v3.4.47](https://symfony.com/blog/symfony-3-4-47-released)
- Removed [abandoned sensio/distribution-bundle](https://packagist.org/packages/sensio/distribution-bundle) and dependencies
- Removed doctrine/doctrine-migrations-bundle integration (no longer required by Mapbender v3.2.4)
- Bump minimum mapbender/mapbender version to 3.2.4 to prevent cross-dependencies on removed doctrine/doctrine-migrations-bundle

## v3.2.3
- Update mapbender/mapbender to [v3.2.3](https://github.com/mapbender/mapbender/releases/tag/v3.2.3)
- Update mapbender/digitizer to [1.4.4](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.4.4)
- Update mapbender/vis-ui.js to [0.2.84](https://github.com/mapbender/vis-ui.js/releases/tag/0.2.84)
- Update mapbender/data-manager to [1.1.4](https://github.com/mapbender/data-manager/releases/tag/1.1.4)
- Update mapbender/data-source to [0.1.20](https://github.com/mapbender/data-source/releases/tag/0.1.20)
- [Demo applications] drop outdated feature info dialog sizing defaults
- [Demo applications] fix invalid CoordinatesDisplay title
- [Demo applications] fix Element titles / tooltips not respecting updated Mapbender defaults
- [Demo applications] prune misc outdated / no longer existant configuration values
- Fix app/console script dependency on current working directory
- Drop php platform override hack
- Drop deprecated loadClassCache calls in entry scripts (deprecated since Symfony 3.3+, no effect on PHP 7)
- Drop example usage for deprecated ApcClassLoader
- Clean up README.md

## v3.2.2
- Update mapbender/mapbender to [v3.2.2](https://github.com/mapbender/mapbender/releases/tag/v3.2.2)
- Misc installation instruction fixes
- Fix autoloading errors in first run of composer update / install
- Add [Digitizer 1.4](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.4)

## v3.2.1
- Update mapbender/mapbender to [v3.2.1](https://github.com/mapbender/mapbender/releases/tag/v3.2.1)

## v3.2.0
- Update mapbender/mapbender to [3.2.0](https://github.com/mapbender/mapbender/releases/tag/3.2.0)
- Bump PHP requirement to >= 7.1
- Ship with Symfony 3.4 LTS by default
- Drop mapbender/digitizer root requirement (currently incompatible)

## dev-release/3.0.8 @ e2740d5
- Update mapbender/digitizer to [1.1.73](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.73)
- Fix misc outdated links in documentation
- Fix app/console dependency on current working directory
- Prune misc outdated configuration values from demo applications
- Prune misc outdated technical documentation (Ubuntu 14 / PHP 5.4 etc)
- Add firewall exclusion for Controller delivery of components (see [Mapbender PR#1352](https://github.com/mapbender/mapbender/pull/1352))
- Drop redunant mapbender/fom root requirement (required by mapbender/mapbender since v3.0.5.4)

## v3.0.8.6
- Update mapbender/mapbender to [v3.0.8.6](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.6)
- Update mapbender/fom to [v3.2.14](https://github.com/mapbender/fom/releases/tag/v3.2.14)
- Update mapbender/owsproxy to [v3.1.6](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.6)
- Update mapbender/coordinates-utility to [1.0.9](https://github.com/mapbender/coordinates-utility/releases/tag/1.0.9)
- Update mapbender/data-source to [0.1.16.2](https://github.com/mapbender/data-source/releases/tag/0.1.16.2)
- Update mapbender/vis-ui.js to [0.2.83](https://github.com/mapbender/vis-ui.js/releases/tag/0.2.83)
- Update wheregroup/jquerydialogextendjs to [2.2](https://github.com/WhereGroup/jquery-dialogextend/releases/tag/2.2)
- Update mapbender/mapquery to [1.1.2131](https://github.com/mapbender/mapquery/releases/tag/1.1.2131)
- Fix untranslated ImageExport (and related Button) titles and tooltips in demo applications
- Fix GpsButton tooltips in demo applications
- Fix some outdated package names in installation instructions
- Add `bootstrap-database` composer task; Reinitializes database schema and content after default database configuration changes
- Remove automatic bundle registration for composer-installed bundles in `\Mapbender\` root namespace  
  NOTE: projects with additional composer-installed bundles may now have to register them explicitly in app/AppKernel.php

## v3.0.8.5
- Update mapbender/mapbender to [v3.0.8.5](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.5)
- Update mapbender/fom to [v3.2.12](https://github.com/mapbender/fom/releases/tag/v3.2.12)
- Update mapbender/owsproxy to [v3.1.4](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.4)
- Update mapbender/digitizer to [1.1.72.1](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.72.1)
- Update mapbender/coordinates-utility to [1.0.7.2](https://github.com/mapbender/coordinates-utility/releases/tag/1.0.7.2)
- Update mapbender/data-source to [0.1.16](https://github.com/mapbender/data-source/releases/tag/0.1.16)
- Update mapbender/vis-ui.js to [0.2.1](https://github.com/mapbender/vis-ui.js/releases/tag/0.2.1)
- Update symfony/symfony 2.8.51 => [2.8.52](https://github.com/symfony/symfony/releases/tag/v2.8.52)
- Update sensio/distribution-bundle from 4.0.39 => 5.0.22
- Update sensio/generator-bundle from 2.3.5 => 3.1.7
- Add optional Symfony WebServerBundle initialization to AppKernel to ease Symfony 3 transition
- Bump php reqs to absolute minimum for Mapbender itself (5.4.30)
- Keep `vendor` directory intact in dist (minus some testing-only deps)
- Drop unused facebook/webdriver dependency
- Disable unused assetic bundle controller
- Remove broken doc:sphinx and doc:api from legacy Phing targets
- Drop phing/phing from require-dev, demote to suggest
- Drop abandoned satooshi/php-coveralls from require-dev, drop inconsequential invocation from Travis config
- Remove remnant config / description of long gone, unmaintained apigen integration
- Avoid deprecated `xargs -i` usages in `dist` command (fixes errors on OSX)
- mapbender_user_basic: fix toolbar border rules, fix zoombar styling
- Drop `composer.json`-defined version, extract version information from git tags instead
- Update `terminal_commands.md`

## v3.0.8.4
- Update mapbender/mapbender to [v3.0.8.4](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.4)
- Update mapbender/fom to [v3.2.9](https://github.com/mapbender/fom/releases/tag/v3.2.9)
- Update mapbender/owsproxy to [v3.1.3](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.3)
- Update mapbender/data-source to [0.1.13](https://github.com/mapbender/data-source/releases/tag/0.1.13)
- Add `cd application` to bootstrap testing server instructions
- Document ext-ldap as a required PHP extension
- Fix invalid html markup and non-cert covered osgeo.org links in demo applications

## v3.0.8.3
- Update mapbender/mapbender to [v3.0.8.3](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.3)
- Update mapbender/fom to [v3.2.6](https://github.com/mapbender/fom/releases/tag/v3.2.6)

## v3.0.8.2.1
- Update FOM to [v3.2.5](https://github.com/mapbender/fom/releases/tag/v3.2.5)

## v3.0.8.2
- Update mapbender/mapbender to [v3.0.8.2](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.2)
- Update mapbender/fom to [v3.2.4](https://github.com/mapbender/fom/releases/tag/v3.2.4)
- Update mapbender/owsproxy to [v3.1.1](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.1)
- Correct README.md server startup instructions ([PR#92](https://github.com/mapbender/mapbender-starter/pull/92))
- Update 'OSM Demo' layer titles in demo applications ([PR#93](https://github.com/mapbender/mapbender-starter/pull/93))
- Add separate composer sub-command reimport-example-apps
- Clean up misc no longer used config values
- Remove misc remnant code and composer command definitions for (no longer bundled) documentation builds

## v3.0.8.1
- Update mapbender/mapbender to [v3.0.8.1](https://github.com/mapbender/mapbender/releases/tag/v3.0.8.1)
- Update mapbender/owsproxy to [v3.1.0](https://github.com/mapbender/owsproxy3/releases/tag/v3.1.0)
- Update mapbender/fom to [v3.2.0](https://github.com/mapbender/fom/releases/tag/v3.2.0)
- Update mapbender/digitizer to [1.1.70](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.70)
- Update symfony/symfony from 2.8.49 to 2.8.51
- Update symfony/security-acl from 2.8.0 to 3.0.2
- Update twig/twig from 1.35.3 to 1.40.1
- Update twig/extensions from 1.5.1 to 1.5.4
- Update doctrine/doctrine-bundle from 1.6.4 to 1.10.2
- Update doctrine/doctrine-fixtures-bundle from 2.3.0 to 2.4.1
- Update doctrine/doctrine-migrations-bundle from 1.3.1 to 1.3.2
- Update doctrine/doctrine-cache-bundle from 1.3.3 to 1.3.5
- Update kriswallsmith/buzz from 0.15.2 to 0.16.1
- Dropped arsgeografica/signing requirement
- Dropped gedmo/doctrine-extensions requirement
- Update misc indirect dependencies
- Fix composer.json meta links ([PR#89](https://github.com/mapbender/mapbender-starter/pull/89))


## v3.0.8
- Update mapbender/mapbender to [v3.0.8](https://github.com/mapbender/mapbender/releases/tag/v3.0.8)
- Update mapbender/digitizer to [1.1.69](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.69)

## v3.0.8-beta4
- Update mapbender/mapbender to [3.0.8-beta4](https://github.com/mapbender/mapbender/releases/tag/v3.0.8-beta4)
- Fix positioning of `legendpage_image` in a4landscape print template ([PR#87](https://github.com/mapbender/mapbender-starter/pull/87))
- Pre-configure Coordinates Utility in demo applications with a `zoomlevel` option ([PR#88](https://github.com/mapbender/mapbender-starter/pull/88))

## v3.0.8-beta3
NOTE: there is no Starter tag v3.0.8-beta2. This tag exists only [in Mapbender](https://github.com/mapbender/mapbender/releases/tag/v3.0.8-beta2).
- Update mapbender/mapbender to [3.0.8-beta3](https://github.com/mapbender/mapbender/releases/tag/v3.0.8-beta3)
- Downgrade mapbender/digitizer to [1.1.68](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.68)
- Update mapbender/fom to [3.1.0](https://github.com/mapbender/fom/releases/tag/v3.1.0)
- Update mapbender/coordinates-utility to [1.0.7.1](https://github.com/mapbender/coordinates-utility/releases/tag/1.0.7.1)
- Update mapbender/data-source to [0.1.11](https://github.com/mapbender/data-source/releases/tag/0.1.11)
- Update symfony/symfony to [v2.8.49](https://github.com/symfony/symfony/releases/tag/v2.8.49)
- Update symfony/monolog-bundle to [v3.2.0](https://github.com/symfony/monolog-bundle/releases/tag/v3.2.0)  
  This fixes [a warning on PHP7.2](http://php.net/manual/en/migration72.incompatible.php#migration72.incompatible.warn-on-non-countable-types)
- Update monolog/monolog to [1.24.0](https://github.com/Seldaek/monolog/releases/tag/1.24.0)
- When installing / updating packages via composer, also clear cache and rebuild components ([7701180](https://github.com/mapbender/mapbender-starter/commit/77011800357fba181fef0aa7728533689e2c7044))

## v3.0.8-beta1
- Update mapbender/mapbender to [3.0.8-beta1](https://github.com/mapbender/mapbender/releases/tag/v3.0.8-beta1)
- Update mapbender/digitizer to [1.2-RC8](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.2-RC8)
- Update mapbender/data-source to [0.1.10](https://github.com/mapbender/data-source/releases/tag/0.1.10)
- Update mapbender/vis-ui.js to [0.2.0](https://github.com/mapbender/vis-ui.js/releases/tag/0.2.0)
- Update mapbender/coordinates-utility to [1.0.6.1](https://github.com/mapbender/coordinates-utility/releases/tag/1.0.6.1)
- Update mapbender/icons to [1.5](https://github.com/mapbender/icons/releases/tag/1.5)
- Removed [mapbender/documentation](https://github.com/mapbender/mapbender-documentation) dependency. The end user
  documentation will no longer be included by default and some of the build amenities have been removed from
  Mapbender Starter. Please see [the main repository](https://github.com/mapbender/mapbender-documentation) for
  standalone build instructions.
- Fix Exception on first bootstrap run, before parameters.yml is created
- Fix missing backend icons after first bootstrap run
- Fix CI build failures ([PR#86](https://github.com/mapbender/mapbender-starter/pull/86))
- Ignore platform reqs in CI / automation environment composer install to allow clean php extension dependency modelling
- Remove nonfunctional remnant apigen-dependent commands; apigen required was removed 11 months ago
- Downgrade default log level from debug => info to reduce log spam and increase visibility of more significant messages

## v3.0.7.7
- Update mapbender/mapbender to [v3.0.7.7](https://github.com/mapbender/mapbender/releases/tag/v3.0.7.7)

## v3.0.7.6
- Update mapbender/mapbender to [v3.0.7.6](https://github.com/mapbender/mapbender/releases/tag/v3.0.7.6)
- Update mapbender/vis-ui.js to [0.0.73](https://github.com/mapbender/vis-ui.js/releases/tag/0.0.73) for performance fix
- Update mapbender/fom to [v3.0.6.2](https://github.com/mapbender/fom/releases/tag/v3.0.6.2)

## v3.0.7.5 (on dev-master)
- Fix automation errors based on concrete branch naming ([PR#84](https://github.com/mapbender/mapbender-starter/pull/84))
- Add Logo to Mapbender Starter and link it in Readme.md ([PR#82](https://github.com/mapbender/mapbender-starter/pull/82))
- Update Mapbender to [v3.0.7.5](https://github.com/mapbender/mapbender/releases/tag/v3.0.7.5)
- Update Owsproxy to [v3.0.6.4](https://github.com/mapbender/owsproxy3/releases/tag/v3.0.6.4), includes Owsproxy dependencies
- Update mapbender/vis-ui.js to [0.0.72](https://github.com/mapbender/vis-ui.js/releases/tag/0.0.72)
- Update mapbender/data-source to [0.1.8](https://github.com/mapbender/data-source/releases/tag/0.1.8)
- Update mapbender/digitizer to [1.1.66](https://github.com/mapbender/mapbender-digitizer/releases/tag/1.1.66)
- Update bundled Composer to [1.6.5](https://github.com/composer/composer/releases/tag/1.6.5)
- Misc ComposerBootstrap cleanups

## v3.0.7.4 (on dev-master)
- Bump mapbender to v3.0.7.4, see https://github.com/mapbender/mapbender/releases/tag/v3.0.7.4
- Shipping yaml applications now use localised text

## v3.0.7.3 (on dev-master)
- Ship with new Coordinates Utility
- AppKernel is now an extension of Mapbender\BaseKernel
- Misc branding updates
- Misc component namespace changes
- Misc documentation improvements
- Separate server:run from main bootstrap script
- Add .editorconfig
- Easier Unit Testing


## dev-release/3.0.6
- Update "a4portrait" Print template
- Improve SCSS to CSS generation performance on windows
- Bypass aggressive class caching / precompilation in dev and test environments
- Update composer binary
- Add composer generate API documentation shortcut "bin/composer docs"
- Add composer update assets shortcut "bin/composer update-assets"
- Add composer shortcut to clear caches "bin/composer clean"
- Improved and cleaned up phing builds
- Cleaned up .travis.yml, allow PHP 7
- Install phantomjs via composer instead of npm
- Add deprecation comment to "generate:*" commands
- Remove old "mapbender2_geometryprovider" service declaration.
- Remove JMS security configuration and bundles
- Use "setasign/fpdi-fpdf" instead of  "toooni/fpdf" library. Adapt PDF_ImageAlpha and PrintService on "setasign/fpdi-fpdf".
- Fix install assets on win/linux machine
- Remove project configuration settings from config.yml
- Remove load dynamic project configuration
- Optimize connection configuration for OCI8 driver (persist=true)
- Extract demo application from mapbender.yml to application/ folder as YAML file for each
- Improve composer package description
- Add apigen/apigen to composer dev
- Optimize bootstrap script for composer, travis and windows
- Rename ScriptHandler to ComposerBootstrap
- Change composer own ScriptHandler order after symfony bootstrap
- Add composer first installation script handler


## v.3.0.6.4
 - Added new documentation file /doc/mapbender_terminal_commands.md (command line)
 - Add oracle database config file

## v.3.0.6.3 - 2017-07-27
 - Fixed regression for for WMS 1.3.0 support (#529)
 - Fixed regression for WMS Scale hint (#584)


## v3.0.6.2 - 2017-07-20
- [Mapbender Starter](https://github.com/mapbender/mapbender-starter/commits/release/3.0.6)
- [Mapbender Core](https://github.com/mapbender/mapbender/commits/release/3.0.6)
- [FOM](https://github.com/mapbender/fom/commits/release/3.0.6)
- [OWS Proxy](https://github.com/mapbender/owsproxy3/commits/release/3.0.6)
- Fix create legend URL
- Reverse to old getScaleRecursive-function in WmsLayerSource Closes: https://github.com/mapbender/mapbender/issues/565
- Fix layer instance administration form sizes Closes: https://github.com/mapbender/mapbender/issues/559
- Update compsoser libraries Fixes: #563
- Merge pull request #52 from mapbender/wirkus-patch-1
- Merge pull request #54 from mapbender/hotfix/map-srs
- map-srs 900913 to 3857
- Update composer libraries. Fixes: https://github.com/mapbender/mapbender/issues/543, https://github.com/mapbender/mapbender/issues/530
- Describe how-to activate universe package on ubuntu 14.04
- Fix create legend URL
- Merge pull request #572 from mapbender/fix/wrong-scaleHint-in-sublayers
- Fix layer instance administration form sizes Closes: #559
- Merge pull request #545 from mapbender/hotfix/imagepathCommand-530
- Merge pull request #553 from mapbender/hotfix/featureinfo-print-trans-button
- Add output for better UX
- Revert commit d11dd2fd1bde139225a388ddb6d125cb24562260
- Merge pull request #570 from mapbender/fix/ruler-unmatching-value-app-backend
- Reverse to old getScaleRecursive-function in WmsLayerSource because of regression bug. Now correct scale and scale hint for sublayer are set
- Change default value for immediate messurment to null and add check if value is set
- Merge pull request #563 from mapbender/hotfix/epsg-code-list
- added EPSG:4839 and EPSG:5243 to the list
- changed trans variable for print button mb.core.featureinfo.popup.btn.print
- changed trans variable for print button mb.core.featureinfo.error.noresult
- Fix FeatureInfo print translations
- Fix initialize search router Closes: #543
- Added Command to update old imagepath of map element / Fix OpenLayers2 image path #530
- Merge pull request #551 from mapbender/fix/search-router-autoclose-after-click
- remove 'move' check on click event
- Add spaces behind foreach and if to satisfy code quality standards
- Remove unused element generator code. Add documentation
- Added reverse axis default for EPSG:31466

## v3.0.6.1
     - [Mapbender Starter](https://github.com/mapbender/mapbender-starter/commits/release/3.0.6)
     - [Mapbender Core](https://github.com/mapbender/mapbender/commits/release/3.0.6)
     - [FOM](https://github.com/mapbender/fom/commits/release/3.0.6)
     - [OWS Proxy](https://github.com/mapbender/owsproxy3/commits/release/3.0.6)
     - Update composer.lock for next bugfix release.
     - Fix a css problem with checkboxes; Fix print scaling
     - Fix typo
     - Let config.php by distributing or build a package
     - Fix composer PSR 4 auto loading
     - Fix symfony cookies reference URL
     - Fix clean modal SCSS file
     - Update composer libraries for 3.0.6.0
     - Update Date in Changelog.md
     - Add "app_dev.php" file for distribution
     - Rename composer rebuild database command
     - Disable generate API documentation generation report
     - Fix and add application by render elements
     - Add WmcEditor Default Parameters for width and height
     - Fix parse dimenstion data
     - Fix vendor specific parameter close button position
     - Add missed VendorSpecific origextentextent property
     - Fix build package name
     - Fix save MetadataUrl as doctine array type
     - Set doctrine version to 1.x
     - Fix save Style, VendorSpecific and WmsLayerSource entities
     - Fix retrive composer build options
     - Add twitter link


## v3.0.6.0

- [Mapbender Starter](https://github.com/mapbender/mapbender-starter/commits/release/3.0.6)
- [Mapbender Core](https://github.com/mapbender/mapbender/commits/release/3.0.6)
- [FOM](https://github.com/mapbender/fom/commits/release/3.0.6)
- [OWS Proxy](https://github.com/mapbender/owsproxy3/commits/release/3.0.6)

    
## v3.0.6.0 - 2017-05-05
    - Fix transalate element titles by import Closes: #46
    - Fix import applications Closes: #45, #47
    - Add php 'mbstring' and 'xml' extensions requirement
    - Fix getting new application entity by slug from database Closes: #43
    - Fix virtual composer repositories and set vendor to WhereGroup:
    - Fix boot strap script installing assets
    - Fix detect operation system and install assets after composer install/update
    - Update to symfony 2.8
    - Set composer library binaries as executable after update or install
    - Split mapbender source and data manager as separate modules
    - Deactivate WMS only valid option by default
    - Fix WmsLayerSource getScaleRecursive method and add annotations
    - Set "robloach/component-installer" library to  "0.x" version
    - Let bootstrap script start web server instance
    - Updating  composer to version 1.4.1 (stable)
    - FIx composer placeholder dependecies
    - Add own "components/placeholdersjs" library repository
    - Fix options variable link in copyright element
    - Add composer log and distribute commands
    - Remove redudant LICENSE file
    - Improve composer build command and rename from build-zip to build. Command options: [zip|tar.gz] [name] [version]
    - composer in bin directory
    - deprecated and unneccessary commands removed from build.xml
    - Add composer archive definition
    - Fix check install assets if new installation
    - Add same LICENSE from root directory to application
    - Add composer "init-example", "install-hard-copy-assets" and "install-sym-link-assets" commands
    - Fix boot strap script for development purposes
    - Remove php-5 installing from bootstrap
    - PSR-2 typo
    - Remove old git installation link
    - Remove JMS security configuraiton and bundles
    - Remove  "jms/security-extra-bundle",  "jms/di-extra-bundle" and "jms/serializer"
    - Remove "kriswallsmith/buzz"composer library (moved as part of owsproxy)
    - Merge update a4landscape.odg print template https://github.com/mapbender/mapbender/pull/486
    - Merge feature/printClient-legendpage-image https://github.com/mapbender/mapbender/pull/486
    - new image for print with legendpage_image.png
    - Set "components/codemirror" archive to zip format
    - Remove static argument type ComposerBootstrap::checkConfiguration
    - Add composer "gen-api-docs" and "gen-user-docs" commands
    - Set 3.0.6 version CONTRIBUTING.md, README.md and parameters.yml.dist
    - Move FOM, Mapbender, documentaiton/_exts and OwsProxy to composer
    - Remove LDAP components to Mapbender/Ldap Bundle as composer 'mapbender/ldap' module
    - Set digitizer version to 1.1.x
    - Fix fix dropdown scrolls background
    - Fix view permission for instance creating and acl handling
    - Fix ComposerBootstrap to use new Event composer type
    - Remove abstract typed class definition from mapbender.geosource.js Hotfix/view permission for instance creating Fix set parameters by create meta data object
    - Fix getting permission for creating objects Add to print and imageexport elements content-type image/png8 and image/png handlling abilites
    - Fix duplicate loads of WMS
    - Fix showing feature iframe informations as tab
    - Fix print second opened feature
    - Fix showing feature informations as tabs
    - Fix show feature info's in mobile apps
    - Enable test travis on PHP 7.0
    - Show line ruler measure reverse (first measure on top)
    - Improve line ruler
    - Deselect base source by creating WMS Instance
    - Fix displaying feature info iframe content and draw container border
    - Fix applciation copy permissions check by not root user
    - Fix feature info reopen dialog if active
    - Impove login, register, password screens (mobile responsive)
    - Update and fix joii component version
    - Improve SCSS to CSS generation performance on windows
    - Update build.properties because of quotes
    - Install instead composer update by build with phing
    - Add release composer command
    - Generate mapbender documentation from composer "bin/composer docs"
    - Add composer generate API documentation
    - Clean build.xml from project specific declarations

## dev-release/3.0.5
- AppKernel is now an extension of Mapbender\BaseKernel

## v3.0.5.4
    - Update "a4portrait" Print template
    - Improve SCSS to CSS generation performance on windows
    - Bypass aggressive class caching / precompilation in dev and test environments
    - Update composer binary
    - Add composer to application/bin as link
    - Add release composer command
    - Add composer generate API documentation shortcut "bin/composer docs"
    - Add composer update assets shortcut "bin/composer update-assets"
    - Add composer shortcut to clear caches "bin/composer clean"
    - Improved and cleaned up phing builds
    - Remove redundant LICENSE and README files
    - Cleaned up .travis.yml, allow PHP 7
    - Install phantomjs via composer instead of npm
    - Add deprecation comment to "generate:*" commands
    - Set PHP platform to 5.3.19 and adds support for PHP 7
    - Remove old "mapbender2_geometryprovider" service declaration.
    - Remove JMS security configuration and bundles
    - Update doctrine dependencies (http://www.doctrine-project.org/2015/08/31/security_misconfiguration_vulnerability_in_various_doctrine_projects.html)
    - Use "setasign/fpdi-fpdf" instead of  "toooni/fpdf" library. Adapt PDF_ImageAlpha and PrintService on "setasign/fpdi-fpdf".
    - Fix install assets on win/linux machine
    - Remove project configuration settings from config.yml
    - Remove load dynamic project configuration
    - Add homepage information
    - Add project version info
    - Add project support information to composer
    - Update composer dependencies
    - Optimize connection configuration for OCI8 driver (persist=true)
    - Change datatables maintainer
    - Extract demo application from mapbender.yml to application/ folder as YAML file for each
    - Improve composer package description
    - Add apigen/apigen to composer dev
    - Optimize bootstrap script for composer, travis and windows
    - Rename ScriptHandler to ComposerBootstrap
    - Change composer own ScriptHandler order after symfony bootstrap
    - Improve .travis.yml configuration
    - Improve installation script handler
    - Add composer first installation script handler
    - Add composer.lock file to get install composer instead of permanent update
    - add parameter wms version to mapbender.yml
    - Fix composer "http-secure" dependencies
    - poi element added useMailto: false to open dialog (in mapbender.yml)
    - added doctrine: dbal: default_connection: default
    - Add templates description to CONTRIBUTE.md

## Mapbender
    - Support reversible layer order per WMS source instance (new dropdown application backend section "Layersets")
    - Support WMS keywords > 255 characters; needs app/console doctrine:schema:update for running installations
    - Extend WmsLoader WMS service compatibility, now matches backend
    - Update WmsLoader example URL to https
    - Skip undefined element classes in Yaml applications, log a warning instead of crashing
    - Fix unbounded growth in "authority" on repeated export / reimport / cloning of applications (#777)
    - Backport doctrine annotations to fix some broken import / export scenarios
    - Various fixes to displaying and handling min / max scale definition from sublayers vs root layers (see pull #787)
    - Backport fix for getting Dimension configuration with open extent
    - Fix strict SCSS warnings when compiling with ruby-sass (closes issue #761)
    - Fix possible URL signing spoof with input URLs missing query parameters (internal issue #8375)
    - Replace usort => array_multisort to skip around PHP bug #50688 when sorting Element names (MB3 issue #586)
    - Merge pull request #765 from mapbender/fix/wms-cleanups-loading
    - Fix http 500 when rendering meta data for a service with undefined contact information
    - Merge pull request #760 from mapbender/fix/unittest-preconditions
    - Merge pull request #747 from mapbender/fix/metadata-serialization-746
    - Merge pull request #743 from mapbender/fix/element-inheritance-639-noconfig
    - Fix getting new application entity by slug from database (issue #739)
    - Changed Opacity for zoombar and toolbar to get a unique button color
    - Support legend URL extraction from styles even if last style has no LegendURL node
    - Merge pull request #699 from mapbender/hotfix/publicFieldsInEntity
    - Merge pull request #657 from mapbender/fix/display-scale-selector-status
    - Merge pull request #456 from mapbender/hotfix/redlining-text
    - No longer persist `WmsInstance->configuration["children"]` (aka "layersets" in frontend), generate only for Application config
    - Fix unknown instance access HTTP status via tunnel (500 => 404)
    - Remove deprecated joii.min.js library
    - Misc code documentation and type annotation improvements
    - Deprecate template and element generator commands
    - Add copyright element width and height configuration options
    - Merge pull request #484 from mapbender/hotfix/scaledisplay
    - Remove unnecessary overlay from mobile SCSS
    - Improved mimetype handling in Print and ImageExport
    - Fix BaseSourceSwitcher initial state immediately on application load
    - Fix duplicate loads of WMS when a layer is going out of scale.
    - allow saving of instances with VIEW right on sources
    - Support print Pdf templates with transparent background
    - Merge pull request #466 from LazerTiberius/feature/immediate-ruler-measurement
    - Improve FeatureInfo behavior in mobile apps
    - Fix feature info reopen if active
    - Make login, register, forgot password and restore password screens responsive
    - Disallow select map, overview and buttons as text
    - Improved PHP7 support
    - Add syntax highlighting for Yaml entry forms
    - Improve cookie and legend handling via "instance tunnel" (used for services secured by basic auth)
    - Better print / export support for secured services
    - Better print support for digitizer features and other geometries
    - Add SymfonyAjaxManager to ManagerTemplate
    - Intergrate bootstrap and refactor/fix administration SCSS files
    - Merge pull request #460 from mapbender/hotfix/default-titlesize512
    - Fix doublets zoom level dots
    - Merge pull request #457 from mapbender/hotfix/featureinfo-css-no-accordion
    - use composer installed phantomjs for tests
    - Add deprecation comment to "generate:*" commands
    - Remove dump asset command
    - fixed featureinfo iframe css
    - Merge pull request #454 from mapbender/hotfix/deleted-layersets
    - Merge pull request #455 from mapbender/hotfix/featureinfo-css-no-accordion
    - Restrict move popups outside of visible area application
    - fixed featureinfo css in certain conditions
    - Remove debugging statement.
    - Merge pull request #453 from mapbender/feature/wmcedit-dialog-size
    - Merge pull request #452 from mapbender/hotfix/add-empty-button-target
    - fixed app loading when layersets were deleted
    - Remove deprecated simple search mobile js handler. #7080
    - added options to configure dialog size
    - added empty button target to dropdown
    - fix update wms scale, scaleHint, MinMax
    - Merge pull request #445 from mapbender/hotfix/metadata
    - Merge pull request #450 from mapbender/hotfix/stored-xss
    - Merge pull request #451 from mapbender/hotfix/manager_app_logo
    - Fix getting element template name in PHP 7
    - Fix element property description typo
    - icon visibility of application in configuration #7082
    - Fix and refactor simple search element. Closes: #409
    - fixed wmclist part of vulnerability
    - Fix element property description typo
    - Fix remove deprecated highlighted layer
    - Merge pull request #449 from mapbender/hotfix/print
    - Merge pull request #448 from mapbender/hotfix/add-styles-to-getmap
    - Fix merge search route default configuration
    - add message for an invalid instance
    - print: fixed multipolygon bug
    - fix target position by layer sorting
    - remove STYLES values for getFeatureInfo Request
    - add 'STYLES' to getMap request
    - Merge hotfix/fix-travis-ci
    - Add element entity repository
    - Fix search router getting default settings
    - print: added visibility check for vector layers
    - print: recognize text color from template
    - Refactor and optimize search router
    - Refactor and automate getting element relevant name for template, administration form, etc.
    - Merge pull request #446 from mapbender/hotfix/poi-element
    - Fix translate SearchRouter default title and tool tip
    - Fix getting default full screen template default properties
    - Translate GPS "no signal" error message
    - Remove old JS test
    - Fix zoom bar level separator displaying for IE 9-11
    - Throw exception if print template doesn't exists
    - Refactor template assets binding
    - transform poi coordinates
    - change layername, change translations
    - add exception_format to OL wms layer
    - Fix getting template static properties
    - set a map dpi to 72
    - Improve and refactor templates
    - Merge pull request #444 from mapbender/hotfix/measure-add-geodesic
    - add geodesic property by activate
    - add geodesic property for measures
    - Refactor and extract Fullscreen template template path to twigTemplate variable
    - Fix handle mobile template button click if target isn't defined
    - Set mobile icon label font weight to normal
    - Fix and improve mobile template button handling
    - Fix displaying layer meta data titles
    - Merge pull request #435 from mapbender/hotfix/import-element
    - Merge pull request #442 from mapbender/hotfix/print-encoding
    - print: fixed input field encoding
    - fix permission for source list
    - Merge pull request #441 from mapbender/hotfix/print-format
    - print: bugfix template format and fontsize
    - Merge pull request #440 from mapbender/hotfix/gps_first_position
    - Update mapbender.element.gpsPosition.js
    - Fix getting default print font name and size
    - gps first position and center on first you know what I mean. #6091
    - Remove EntityAnnotationParser empty comment
    - Fix typos in WmcParser
    - Deprecate Mapbender2UserProvider
    - Merge pull request #434 from mapbender/hotfix/layer-switch-fix
    - Merge pull request #437 from mapbender/hotfix/print-fontsize
    - Merge pull request #436 from mapbender/printclient-labelinputwidth-patch
    - print: bugfix parsing fontsize from pdf-template
    - Update printclient.scss
    - Fix changed paramer of isElementGranted to the new ElementEntity type
    - Revert messed up format
    - Change import of  Mapbender\CoreBundle\Entity\Element to use Mapbender\CoreBundle\Entity\Element as ElementEntity;
    - fixed #6577 (display of wrong back buffer when switching on layers)
    - Merge pull request #424 from mapbender/hotfix-for-5988
    - Merge pull request #433 from mapbender/hotfix/print-fontsize-check
    - Fix print default font size setting
    - Merge pull request #430 from mapbender/hotfix/print
    - Remove dump role names fix
    - Fix and union application save validation logici
    - Improve ApplicationController asset cache check
    - Fix print default font size setting
    - Merge pull request #432 from mapbender/fix/acl-handling
    - Improve application TestBase
    - Fix coping application
    - Fix application creation form check
    - Remove auto completion from WMS source login form
    - Merge pull request #431 from ThorstenHi/bugfix/addSource
    - fixed add wms source, disable autocomplete password
    - print: fixed template import with fpdi
    - Merge pull request #429 from ThorstenHi/bugfix/xss
    - Improve ACL handling
    - Fix Map POI XSS
    - Fix get iterratable ACL OID's list
    - fix XSS vulnerability
    - fixed php7 print bug
    - Fix manager application common tab DE/EN translation
    - Revert Application component changes
    - Refactor Application component
    - Refactor Application, Template components and Fullscreen template
    - Refactor full screen template and handler
    - Refactor Application->getElementById
    - Merge pull request #420 from mapbender/feature/fractionDigits-number-coordinatedisplay
    - Check der Datenquelle aus Ausgabetemplate entfernt s.a. Ticket: Datenquelle OK entfernen
    - Fix register mobile application event handler on "moveend"
    - Refactor PDF_ImageAlpha
    - Use "setasign/fpdi-fpdf" instead of  "toooni/fpdf" library. Adapt PDF_ImageAlpha and PrintService on "setasign/fpdi-fpdf".
    - Simplify WmsBundle translations from XML to YAML hierarchical structure
    - Simplify WmcBundle translations from XML to YAML hierarchical structure
    - Simplify PrintBundle translations from XML to YAML hierarchical structure
    - Simplify KmlBundle translations from XML to YAML hierarchical structure
    - Simplify CoreBundle translations from XML to YAML hierarchical structure
    - Simplify translations from XML to YAML hierarchical structure
    - Refactor and add default application manager translations
    - Improve  application manager russian translations, powered by Zhandos (http://osgeo-org.1560.x6.nabble.com/Proved-russian-translation-file-td5261873.html)
    - Use new  application manager messages translations. Refactor ApplicationController.
    - Translate application manager messages to DE and EN dictionaries
    - Refactor imports of WelcomeController
    - Extend ApplicationController by WelcomeController. Remove redundant code.
    - Refactor ApplicationController and TranslationController
    - Refactor Manager ApplicationController
    - Refactor Import/Export components
    - Improve tab navigation to use keyboard (TAB)
    - Fix display workflow buttons by editing of application element ACL
    - Fix find object ACL (add try-catch block)
    - Add ability to see which security permissions are set for an element (or some other object)
    - Extract administration border radius variables
    - Refactor ApplicationController security permission check
    - Remove EntityHandler unnecessary comment
    - Refactor ApplicationController export methods
    - Remove ExchangeNormalizer unnecessary head comment
    - Fix export permission check call
    - Fix SecurityContext permission check
    - Fix display administration navigation item active icon
    - Display elements by edit which has ACL in different color
    - Refactor element controller security method
    - Refactor Element entity class
    - Refactor manager.scss
    - Improve tab navigation container style
    - Improve buttons and input elements styles
    - Improve tab navigator style
    - Merge pull request #419 from mapbender/feature/show-source-id-by-add-instance
    - Improve manager tab navigator style
    - Fix _icons.scss font
    - Improve application list navigation style
    - Improve application list border style (radius=8)
    - Refactor ManagerBundle
    - Refactor application core components
    - Core application components refactored
    - Core entities refactored
    - Application refactored
    - Refactor WelcomeController
    - Improve ExportHandler documentation
    - Improve ExchangeJob documenation code format
    - Refactor and clean code of Element entity
    - Merge pull request #421 from mapbender/hotfix/wms-doctrine-entities
    - Refactor ElementController
    - set Many-To-One, Unidirectional for WmsInstanceLayer->WmsLayerSource
    - Fix WmsSource annotations
    - Fix WMS entities
    - Revert reload parameter bag service
    - Revert about dialog changes
    - Add composer clean lifecycle
    - set number of fractionDigits for srs.units=grad to +5
    - show source id by create an instance
    - Remove featureInfo IFrame background color (switch to transparent)
    - add persist application by components change
    - Merge pull request #412 from mapbender/feature/load-app-config-dynamic
    - use wms version at wmsloader
    - add initDropdown for select
    - Improve feature info response table styling
    - fix hide wms tiled for outer scale
    - Rebuild cache after save application in production mode
    - add GET parameters for dynamic loading
    - add dynamic application's configuration loading
    - Check if user logged in security.context
    - Fix security context annotation return type
    - get supported projections from Proj4js.defs
    - Fix load EPSG data
    - Merge pull request #410 from mapbender/feature/wms-1.3.0
    - support wms version 1.3.0 in overview element
    - fix default version for mapbender.yaml wms
    - add srs for poi
    - round a poi coordiante
    - Merge pull request #411 from mapbender/release/305
    - Add SCSS validation before save application
    - update featureinfo element
    - add support for wms v1.3.0
    - Merge pull request #405 from mapbender/hotfix/change-layer-options
    - add version, exception_format to WmsInstance; use version for GetFeatureInfo
    - Refactor element generator
    - Fix DataFixtures EPSG's import and implement EPSG's update
    - Merge pull request #406 from mapbender/hotfix/exchange
    - Remove obsolete mapbender.element.zoombar.css file
    - Change zoom pane vertical margin to 4 dots
    - Fix overview map navigation in IE9
    - Refactor mapbender overview  element javascript
    - Refactor overview style sheets
    - Remove obsolete mapbender.element.overview.css
    - fix default visibility for a layer
    - fix name 'application' at form type
    - optimize applications import/copy for not mysql, sqlite, spatialite
    - Add SecurityContext get user role names
    - Set default YAMLDataTransformer indentention=2
    - fix copy application
    - Improve edit YAML styling
    - optimize an export
    - Add HTMLElement handling  of service and DataStore configuration
    - Refactor ApplicationController and Application
 
### FOM

    - Restrict move popups outside of visible area application
    - Merge pull request #19 from mapbender/hotfix/stored-xss
    - fixed dropdown part of vulnerability
    - Merge hotfix/fix-travis-ci
    - Short user name russian translation
    - Deprecate FOM SharedApplicationWebTestCase
    - Improve tab navigation to use keyboard (TAB)
    - Fix find object ACL (add try-catch block)
    - Add ability to see which security permissions are set for an element (or some other object)
    - Extract administration border radius variables
    - Add new ACL has and get methods
    - Improve login box screen
    - Improve application list navigation
    - Fix embedded login screen if session time is out
    - Improve DoctrineHelper to get create tables for new entities if connection is sqlite
    - Fix xls ExportResponse decode utf-8
  
  
### OWSPROXY
 
    - Add README.md (README.md)
    - Add communication schema (src/OwsProxy3/CoreBundle/Documentation/communication.puml)
 
 
 
## v3.0.5.3 - 2016-02-04
    - Improve application manager button style
    - Set login menu default font family
    - Optimize default application preview images
    - Merge export alpha layers as image
    - Fix deactivate GPS button in Firefox
    - Fix search router zoom to feature twice
    - Redraw search router selected feature after zoom
    - Fix and refactor GPS locator widget
    - Fix deactivate gps button
    - Improve reset form styles
    - Fix simple search side pane styling
    - Fix simple search admin type styles
    - Fix set layerset name
    - Fix  search router horizontal scroll and remove result styles
    - Refactor search router
    - Improve SimpleSearch element styling
    - Fix reset password page styling
    - Add search router horizontal scrolling
    - Fix digitizer version
    - Fix search router reset last results and improve styles
    - Fix add user group with same prefix
    - Improve poi link dialog style
    - Disable error message fade in effect
    - Fix composer package versions
    - Change version number
    - Fix search router element input label (title)
    - Fix add group with same prefix in security tab
    - Fix select element global listener
    - Merge feature/3.0.5_mapbender.yml_redline
    - Merge feature/3.0.5_mapbender.yml_featureinfog
    - Merge pull request #17 from mapbender/feature/printtemplates
    - Improve srs and scale selector styles
    - Fix import/export region properties
    - Set layer tree title max length = 40
    - Fix composer versions to stable
    - Fix RegionProperties entity property visibility
    - Angepasste Templates #5530
    - Added all parameters for element featureinfo to demo mapbender_user and mapbender_user_basic
    - Added redline to mapbender.yml in mapbender_user
    - Fix print element by merging https://github.com/mapbender/mapbender/pull/394
    - Fix digitizer composer dependencies
    - mapbender.yml removed unnecessary parameter
    - Fix jQuery version to 1.11.2 (release 1.11.3 works not well yet)
    - Fix jQuery version to 1.x
    - Update submodules
    - Fixes sass compiler fails on Linux 32-bit
    - Merged mapbender/mapbender#390
    - Merge pull request #13 from mapbender/hotfix/readme-5488
    - Fix assets caching
    - Versions readme file #5488
    - Add note on the branches in the adjusted readme file #5488
    - Adjusted readme file #5488
    - Fix composer phpunit version for symfony v2.3
    - Fix coordinates display
    - Fix set active tab after form saving
    - Fix import application from mapbender.yml
    - Update composer.phar
    - Remove global $.ajax proxy rewriting
    - Change parameters.yml.dist version number
    
### Mapbender

    - Fix on/off layer visibility
    - Improve application manager button style
    - Improve tool and zoom bar icon opacity
    - Set login menu default font family
    - Shrink mapbender logo
    - Remove console.log froim mapbender.element.gpsPosition.js
    - Merge export alpha layers as image
    - Fix deactivate GPS button in Firefox
    - Fix search router zoom to feature twice
    - Redraw search router selected feature after zoom
    - Print: fixed legend size
    - ImageExport: fixed opacity
    - Fix and refactor GPS locator widget
    - Fix deactivate gps button
    - Fix simple search side pane styling
    - fix featureinfo url
    - Fix simple search admin type styles
    - Fix set layer name
    - Improve search router style for mobile template
    - Disable wrap search router table header text
    - Fix  search router horizontal scroll and remove result styles
    - Refactor search router
    - Improve SimpleSearch element styling
    - Fix HTTP/HTTPS feature info requests
    - Fix check map tileSize option
    - Add search route horizontal scrolling
    - Fix search router reset last results and improve styles
    - Improve poi link dialog style
    - Disable error message fade effect
    - Remove TODOs.rst because becomes outdated
    - Fix search router element input label (title)
    - Improve scale and srs selector styles
    - Fix import/export region properties
    - Set layer tree title max length = 40
    - Set protocol into featureinfo url from browser
    - Fix RegionProperties entity property visibility
    - Change Application $regionProperties property to protected
    - Merge pull request #394 from mapbender/feature/print-sidepane
    - Print: sidepane-print changed button style and behaviour
    - Print: bugfix type parameter
    - Improve SearchRouter table header padding
    - Print: fixed sidepane usage
    - Remove normalize.css becourse bootstrap alredy include them
    - Remove using normalize.css because bootstrap.css already includes them
    - Hotfix print twig template generation
    - Fix transform a clicked feature geometry to map srs
    - Fix result table selection checkbox  position and selected row color
    - Add default values for SearchRouter
    - Add with/height for SearchRouter dialog
    - Merge pull request #393 from mapbender/hotfix/print-legend
    - Merge pull request #384 from mapbender/feature/print-sidepane
    - Print: added missing translations
    - Print: added missing button label
    - Print: fixed legend bug
    - Print: fixed dynamic text position
    - Merge pull request #392 from mapbender/feature/redlining-without-dialog
    - Fix wms layer get legend from self, formate code
    - Fix coordinates width  at bottom menu
    - Remove CSS transition animation for map tile load
    - Fix annotation typo
    - Fix HTML element assets paths
    - Fix max height for searchRouter result
    - Fix a layer validation for an instance
    - Fix: add only valid instances into layerset configuration
    - Fix layertree: remove theme by missing sources
    - Fixes sass compiler fails on Linux 32-bit #389
    - CP#5164: GPS accuracy fix
    - Improve assets generation and caching mechanic and fixes: #388.
    - Improve CSS cache modification check
    - Cache CSS for production environment
    - PrintClient: sidepane usage
    - Merge pull request #383 from mapbender/hotfix/application-copy-sqlite-5018
    - Merge pull request #382 from mapbender/release/3.0.5
    - Merge pull request #381 from mapbender/hotfix/print-optionalfields
    - PrintClient: fixed required input fields
    - Fix coordinatesdisplay: set default values for options
    - Fix application copy for sqlite: add 'flush' after 'persist'
    - Fix copy application
    - Add check if Storage suported
    - Fix set active tab after form saving
    - Add translations for redlining
    - Display type 'element' for a redlining element
    - Fix show/hide redlining
    - Fix import application from mapbender.yml
    - Remove global $.ajax proxy rewriting
    - Merge pull request #376 from mapbender/develop
    
### FOM

    - Improve reset form styles
    - Fix reset password page styling
    - Fix add user group with same prefix
    - Fix select element
    - Fix add group with same prefix in security tab
    - Fix select element global listener
    - Improve scale and srs selector styles
    - Fix FOM composer.json error
    - Merge pull request #18 from mapbender/hotfix/user-activate
    - Update messages.ru.xlf
    - Add 'de' translations
    - Translate default fos messages, reformate code
    - Fix reset password email body text
    - 5190 change format of forgot password mail
    - Changed translation typo de
    - Merge branch 'hotfix/user-activate' into release/3.0.5
    - Fix activate/deactivate only other users
    - Add aktivate a self registrated user
    - Merge pull request #17 from mapbender/hotfix/changelog-5489
    - Add changelog.md information
    
### OWSPROXY
    - Merge pull request #4 from mapbender/hotfix/changelog-5489
    - Add changelog.md information #5489



## v3.0.5.2 - 2015-10-27
    - Copy applications: User-Rights and groups are copied. The user who copied the application becomes owner of the copied application.
    - FOM: Changes in behaviour of wrong logins and user locking. It is only shown that the login failed, independent if the user exists or not.
    - Fixed error message when creating a user with a too short password.
    - Print: Fix of replace pattern.
    - Print: Fix if a wrong configured WMS has special characters (%26) in the legend URL.
    - Image export in Firefox.
    - WMC Loader: Loading WMC and Behaviour of BaseSources.
    - BaseSourceSwitcher: Tiles of a not visible service are not pre-fetched.
    - BaseSourceSwitcher: If a group is defined, only one theme is switched on.
    - SearchRouter: Fix of quotes for table-names.
    - Copy applications: Fix of the search in the copied application.
    - Simple Search: Catch the return key.
    - FeatureInfo: Add WMS functionality and WMS Loader.
    - Icon Polygon is visible in the toolbar of applications.
    - Icons, which are not based on FontAwesome also work in the mobile application.
    - Administration of the map element: The view of the configuration dialog in the backend starts on top.
    - Administration data source: No form data auto-complete from the browser for username and password.
    - Mobile application: Design in Firefox for Android.
    - Update 3.0.4.x: FeatureInfo autoopen=true is kept.
    - Doku: FOM UserBundle translation and additional information for failed user logins.
    - Doku: URL parameter scale in map element.
    - Doku: WMC Loader and KeepSources.

## v3.0.5.1 - 2015-08-26
    - Map: OpenLayers TileSize: You can set the tile-size for the map. Default: 256x256.
    - Map: Delay before Tiles: For WMS-T, for example with temporal parameters (in future)
    - Print: Show coordinates in PDF print
    - Print: get print scale depending on map-scale
    - Print: print legend_default_behaviour
    - Print: add print templates with the + symbol
    - Print: user-defined logo and text
    - Layertree: loading symbol and exclamation mark symbol.
    - Layertree: zoom Symbol not for layers without a BBOX information
    - WMS Reload: FeatureInfo
    - WMS Reload: some WMS couldn't be reloaded.
    - Export/Import of application and miscellaneous bugfixes
    - WMC-Editor and WMC-Load fixes.
    - WMC from a Mapbender 3.0.4.1 application
    - Tile buffer and BBOX buffer fixes
    - FeatureInfo: Fixes in design and when shown as an Accordion Panel
    - FeatureInfo: Print
    - Wrong Jquery-UI link in layerset instance
    - Save Layerset and Save Layout leaves you on the page
    - Classic Template: SCSS corrections
    - Mobile Template: Bootstrap message hides close button
    - Mobile Template: close SearchRouter window
    - Mobile Template: Mozilla Firefox Fixes on layout
    - Backend: Layerset Filter and +-Buttons doesn't hide everything anymore
    - upgrade joii to 3.1.2
    - composer.json upgrade version of Digitizer to 1.0.*
    - Documentation of the JS-UI Generator (Form-Generator): https://github.com/eSlider/vis-ui.js

## v3.0.5.0 - 2015-07-01
    - a map parameter "layerset" is renamed into "layersets" and represets a list of layersets
    - WMS Update
    - Digitize Functionality
    - Print with legend
    - configurable Layertree
    - Mobile Template
    - SASS Compiler
    - addvendorspecific
    - advanced features for HTML element through formgenerator
    - New button collection
    - advanced behaviour of featureInfo dialog (keep styles, only open tabs for hits, width and height for FeatureInfo dialog)
    - add parameter on start of a Mapbender3 application (change srs, poi, bbox, center)
    - Symfony Update 2.3.30
    - new translations for Portuguese and Russian
    
## v3.0.4.1 - 2015-01-23
    - option 'removelayer' added into layertree menu
    - parameter 'layerRemove' removed from layertree configuration
    - container accordion structure changed
    - import / export from applications added (without acls)
    - display layer metadata
    - Frontend: Sidepane Accordeon Legend is displayed without horizontal Scrollbar
    - Backend: WMS Instanz configuration - contextmenu for layers shows wrong ID (only instance ID)
    - Frontend: Legend - displays WMS Information although the checkbox Show
    - Frontend: Layertree - contextmenu zoomlayer does not use the layer extent
    - Backend: Add Source with user/password - informations is added to field originUrl not to fields user and password
    - app/console mapbender:generate:element fixed errors
    - bug visiblelayers fixed
    - WMS with authentication saves in table mb_wms_wmssource username and password
    - no metadata for applications coming from mapbender.yml definition (no entry in context menu)
    - copy an application via button on application fixed
    - print template resize northarrow, overview added
    - improved screenshot for application handling
    - https://github.com/mapbender/mapbender/milestones/3.0.4.1

## v3.0.4.0 - 2014-09-12
    - Switched to MIT license
    - Added parameter group for element BaseSourceSwitcher to be able to create a menu bar with groupname as title of the menu
    - Added accordion container for SidePane
    - Upgrade to Symfony 2.3
    - fixed validate WMS GetCapabilities document
    - fixed layer sorting at backend
    - fixed application copy
    - Added spanish translation (thanks to Mario Torres)
    - Added custom buffer/ratio for gridded layers (requires DB update)
    - Added element for generic HTML
    - Added custom CSS editor for applications (requires DB update)
    - fixed element saving bug
    - use degrees as unit fallback when none are provided by SRS definition
    - added screenshot management to application editing
    - enhanced CSS URL rewrite to be more dynamic depending on apps URL rewriting
    - patched OpenLayers with unreleased upstream fixes
    - enhanced GPS position element (remove marker on disable, position averaging)
    - properly remove proxy from WMS URLs before printing
    - display WMS metadata valdiation results
    - fixed application copy bugs
    - region properties added (normal/tabs/accordion)
    - patched OpenLayers 2.13 with fixes for proper IE8-10 behavior
    - prevent unsaved element forms to be closed accidentally
    - added CSS editing to application editing
    - added generic HTML element
    - Codemirror updated
    - workaround weird fileinfo behavior during print
    - added scalebar to print
    - enhanced SimpleSearch preprocessing with regex and sensible Solr defaults
    - travis-ci.org integration for automated testing
    - SearchRouter enhancements (z-index, results counter)
    - GPS position can make the map follow it's position
    - More WMS metadata validation, handling and displaying
    - FeatureInfo can have custom data handlers
    - configurable buffer/ratio property for WMS instances
    - print using layer opacity
    - SearchRouter feature styles can be configured
    - SearchRouter: autocomplete enhancements, feature garbage collection, more configration options
    - responsive application templates
    - added session entity
    - delete ACL with delete
    - region properties (tabs/accordion)
    - fixed application copy bugs
    - popups can prevent close when unsaved data
    - dynamic user profile insertion
    - enhanced autocomplete with query term preprocessing
    - fixed popup focus behavior
    - travis-ci.org integration for automated tests
    - external user/group providers can be configured instead of FOM
    - Enhanced exception handling
    - Fix cURL behavior when closing connections
    - Added user-agent "OWSproxy3"
    - Added request/response logging
    - Oracle support for logging
    - import/export of applications/sources
    - https://github.com/mapbender/mapbender/milestones/3.0.4.0

## v3.0.3.2 - 2014-04-04
    - Added HttpBasicAuthListener to WMS loading for for safely setting the auth header
    - WMSProxy passes auth challenges (HTTP 401) down to client
    - fixed fullscreen alternative Template
    - SearchRouter reset results in map
    - https://github.com/mapbender/mapbender/issues?milestone=14

## v3.0.3.1 - 2014-03-20
    - Disabled WMS validation as it renders many services unusable.
    - Made WMC editor resizable and taller

## v3.0.3.0 - 2014-03-17
    - Added function for validate WMS GetCapabilities documents
    - ACL for Elements added
    - Parameter "BaseSource" for SourceInstances added
    - Closed XSS vulnerability which required admin permissions
    - Added cache for compiled static assets
    - new parameter mapbender.static_assets, defaults to true
    - new parameter mapbender.static_assets_path, defaults to web/css/application
    - Compiled assets get cached to the directory set with the aforementioned parameter
      - This directory needs to be cleared before packaging or updating.
      - This directory needs to be refreshed before packaging.
    - Element Legend: option 'noLegend' removed
    - Translation for en,de added
    - ZoomBar option component 'zoom_in_out' added
    - added cookie_secure: false and cookie_lifetime: 3600 to parameters.yml http://symfony.com/doc/2.1/reference/configuration/framework.html#cookie-lifetime
    - Enhancements for Search-Router für SQL-Suchen (Selectboxes, Distinct)
    - WMC Editor and LoaderWMSLoader Enhancement add WMS via link
    - Sketch to draw temporary objects
    - POI - Meetingpoint
    - Imageexport to generate png or jpg
    - Change WMS Collection via button (BaselayerSwitcher)
    - Print with overview
    - Print define optional fields
    - Print define replace pattern
    - Sidepane with different elements (chnage via button)
    - Layertree context menue to change opacity and to zoom to layer
    - Open application with parameters (f.e. position)


## v3.0.2.0 - 2013-11-26
    - Signer for OwsProxy added
    - Properties for regions added
    - Sketch feature (circle) added
    - Update layertree changed
    - Funktion Model.changeLayerState added
    - LoadWms load declarative WMS added
    - Dispatcher for declarative Sources added
    - Dropdown lists are now scrollable
    - Micro designfixes
    - Search router design added
    - New button icons for wmc editor and loader added
    - console.* stubs
    - Proxy security: Only pass correctly signed URLs
    - Allow for multiple application YAML files

## v3.0.1.1 - 2013-09-26
    - The development controller app_dev.php is limited to localhost again

## v3.0.1.0 - 2013-09-12
    - Fixed visibility toggle for elements and layers
    - Hide sidepane if empty
    - Parameter/Service 'mapbender.proxy' removed
    - Parameter 'mapbender.uploads_dir' added
    - Application's directory added
    - Added wgs84 print
    - Added printclient parameter file_prefix
    - Added default action for elements
    - Splited `frontend/components.js` into `sidepane.js` and `tabcontainer.js`
    - Remove unused images references
    - New popup architecture
    - Add application dublication
    - Prepare `collection.js` for dynamic element properties (full support in next versions)
    - Fix some micro css bugs
    - Map scale bugs fixes
    - Move checkbox script into `checkbox.js`
    - Merge checkbox frontend and backend script
    - Move dropdown script into `dropdown.js`
    - Merge dropdown frontend and backend script
    - Fix some dropdown bugs
    - Fix some layertree css bugs
    - Fix some popup css bugs
    - Micro design fixes
    - Remove unused jQuery-UI CSS
    - Add more translation wraps
    - Add `widget_attribute_class` macro for forms
    - Element position moved from `mapbender_theme.scss` to `fullscreen.scss`
    - Add new frontend template - Fullscreen alternative
    - Frontend jQuery upgrade to 1.9.1/1.10.2 (jQuery UI)

## v3.0.0.2 - 2013-07-19
    - Removed incorrect feature info function `create`
    - Set overlay `position` to `fixed`
    - PrintClient Admintype added
    - PrintClient Configuration Parameter changed
    - Instance view - order of `on` and `allow` changed
    - Disable WMCBundle - Available in the next versions
    - Parameter unitPrefix added to ScaleDisplay
    - normalize.css compressed
    - Popup decrease `max-height`
    - Forgot, Register success and error messages designed
    - Restructured login, forgot and success templates
    - Elements overview is sorted by asc
    - Wmsloader popup - set fix `width`
    - Design added to ScaleDisplay
    - Fixed manager logo positioning
    - Fixed design of print client and map forms
    - Fixed double *delete* label at layers and elements
    - Fixed html and body `height`
    - Fixed Firefox font bug
    - Fixed printclient tooltip bug
    - Fixed ScaleDisplay position bug
    - Added POI (0...n) and BBOX URL parameter handling
    - Fixed ACL creation during user creation (#52)
    - Fixed ACL creation during group creation (#53)
    - Enhanced ACL creation during service creation (#54)
    - Honor published attribute on YAML-defined applications (#42)

## v3.0.0.1 - 2013-06-07

## v3.0.0.0 - 2013-05-31
    - First version
