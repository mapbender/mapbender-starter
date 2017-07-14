# Developer Guide

The Mapbender team welcomes contributions from all members - so you are welcome to join us in the development!

Third-party patches are essential for the preservation of high standards in [Mapbender].

We simply cannot access the huge number of platforms and myriad configurations for running [Mapbender]. 

We want it as easy as possible to carry out changes to get the [modules] in your environment to run. 

There are a few [guidelines][rules] that we need contributors to follow so that we can have a chance of keeping on top of things.

# Architecture 

Mapbender is based on a [Symfony framework] and uses [composer] to manage external and internal libraries as own [modules][module].

# Installation      


## Preparing system

To get started with the development you will need PHP interpreter and some additional libraries.
  
### Preparing Ubuntu 16.04 

```sh
sudo apt-get install php7.0 php7.0-xml php7.0-mbstring php7.0-pgsql php7.0-gd php7.0-curl php7.0-cli php7.0-sqlite php7.0-intl php7.0-json sqlite curl openssl
```

### Preparing Ubuntu 14.04 

Activate universe repoisitory:

```sh
sudo add-apt-repository universe
```

Update package list:

```sh
sudo apt-get update
```

Install php interpreter and modules:

```sh
sudo apt-get install php5 php5-xml php5-mbstring php5-pgsql php5-gd php5-curl php5-cli php5-sqlite php5-intl sqlite curl openssl
```

## Cloning project source

### via SSH
```sh
git clone git@github.com:mapbender/mapbender-starter.git mapbender-starter
```

or 

### via HTTP

```sh
git clone https://github.com/mapbender/mapbender-starter.git mapbender-starter
```

## Switch to project directory
```sh
cd mapbender-starter
```

## Run bootstrap script 

Running the bootstrap script takes some time to get the required libraries and to prepare the project configurations. Bootstrap saves [Mapbender] starter configurations in a `application/app/db/demo.sqlite` configuration file. After that, a SQLite testing database is created. In the end, the script starts an integrated webserver in your terminal. For more on that, check [start webserver]. Symfony configurations are located under `application/app/config`. 


```sh
sh bootstrap
```


## Start web-server
```sh
app/console server:run
```

After executing the command run, your console describes how you can view web application in your browser:
```sh 
Server running on http://localhost:8000
```

Open the displayed URL from your console output message in your favorite browser. 

For development reasons, it is recommended to use Chromium (Chrome) or Firefox.

If you want to log in, you have to use the standard combination (name: root / pw: root). Please change these patterns if you want a secure access.

To stop the server from running, just press ctrl-C or close your console.

That's it! 

*The developer installation is only useful for solo development purposes and should be optimized for production or co-working systems.*


# Modules

Module is a new part of the [Mapbender] concept, based on [Symfony modularity rules](http://www.symfony.com) 
and [composer] dependency manager. 

Special builds can be created that exclude subsets of Mapbender functionality. 

This allows smaller custom builds when the builder is certain 
that those parts of Mapbender are not being used. 

For example, it is possible to create an application which only uses map view and did not need [Digitizer] functionality.

Future [Mapbender] releases may be able to exclude any additional modules apart from the core application. 

In the past, the development bundles were part of the git [submodules]. 

Today, each module should be in its own git repository 
and reuse the same directory structure. 

## Rules

It's __very important__ to follow enclosed rules:

Each module is:

* [git] repository
* [Symfony] bundle
* mapbender [bundle]
* [composer] library (has [composer] definition)

Each module should have:

* only one [bundle]
* only one primary namespace 
* identical structure
* own [license] file
* own function description [README] file
* own [CONTRIBUTING].md that describes how other developers should install, setup and contribute in it
* own [tests] relevant to new [features], [elements] or functionality

Write your code using PSR-2, a coding [style guide] standard. 

# Bundles 

A bundle is a set of functionality (similar to a library) which can be created and used outside of the [Mapbender].
The goal of the Bundle is to restrict the usage of global name spaces and optionally switch, swap and extend the [Mapbender] functionality.

## Bundle structure
 
A Bundle contains a special set of folders and files:

* **Command/** - Contains commands. Read more about commands [here] (http://symfony.com/doc/current/components/console/introduction.html#creating-a-basic-command)  
* **Controllers/** - Contains _controllers_ in other words public [API]'s. 
* **Component/** - Contains _components_ in other words _services_, 
    this contains business logic in classes. The _components_ are used by controllers or other components.
* **DataFixtures/** - Fixtures are used to load a controlled set of data into a database. This data can be used for testing or could be the initial data required for the application to run smoothly.
* **DependencyInjection/** - Contains only one file, this makes [components] in _magical_ way available as [services], 
    if they are _registred_ in _Resources/config/services.xml_ [bundle] folder.
* **Documents/** - Contains documents related to the [bundle]. [MD] for text and [PUML] for charts formats are preferred.
* **Exception/** - Contains exceptions.
* **Element/** - Contains Mapbender [elements]. This folder isn't [symfony] conform.
* **Element/Type** - Contains Mapbender [elements] administration types/forms.
* **Entity/** - Contains entities.
* **EventListener/** - Contains event listeners.
* **Resources/config/** - Contains configurations.
* **Resources/public/** - Contains web resources ([CSS], JS, images).
* **Resources/views/** - Contains [twig] and php templates.
* **Resources/translations/** - Contains [translations].
* **Template/** - Contains mapbender [templates].
* **Tests/** - Contains [PHPUnit] and functional tests.
* **composer.json** - Describes the bundle as [composer] package/library. [Example](https://github.com/mapbender/mapbender-digitizer/blob/master/composer.json)
* **LICENSE**  - Contains [LICENSE] text.
* **README.md** - Contains [README] text.
* **CONTRIBUTING.md** - Contains [CONTRIBUTING] text.
* **MapbenderNameBundle.php** - Bundle description file, this registers and makes available bundle [elements], [templates],  [manager controllers] and [layers] register.

Read more about best practices for reusable [bundles] [here](http://symfony.com/doc/2.3/cookbook/bundles/best_practices.html).


## Bundle creation

Create a [git] repository outside of Mapbender, as your own project.

```sh
cd ~/Projects
mkdir new-awesome-bundle
cd new-awesome-bundle
git init 
```

In order to create a [bundle], please take a look at the [bundle structure](#Bundle%20structure). 

**Don't forget to follow [module] [rules]**!


### Create bundle description class

Bundles can contains [Templates], [Elements], [Roles], administration manager menu items or ACL classes. 
Bundle class file describes which Templates, Elements or ACL classes are delivered and available for the bundle.
The name of bundle description  file should contain full name of bundle and class name like this: `MapbenderMapbenderNameBundle.php`

Description class should extend the *MapbenderBundle* class


#### Register bundle components

Methods available to rewrite from *MapbenderBundle*:

* *getElements* - Should return a list of element classes provided by the bundle. Each entry in the array should have a fully qualified class name.  See [source](https://github.com/mapbender/mapbender/blob/release/3.0.6/src/Mapbender/CoreBundle/MapbenderCoreBundle.php#L33) for an example.
* *getTemplates* - List of template classes provided by bundle. Each entry in the array is a fully qualified class name.  See [source](https://github.com/mapbender/mapbender/blob/release/3.0.6/src/Mapbender/ManagerBundle/MapbenderManagerBundle.php#L33) for an example. 
* *getManagerControllers* - List of controllers to be embedded into administration manager interface. The list must be an array of arrays, each giving the integer weight, name, route and array of route prefixes to match against. See [source](https://github.com/mapbender/mapbender/blob/release/3.0.6/src/Mapbender/ManagerBundle/MapbenderManagerBundle.php#L11) for an example. 
* *getACLClasses* - List ACL bundle classes. See [source](https://github.com/mapbender/mapbender/blob/release/3.0.6/src/Mapbender/CoreBundle/MapbenderCoreBundle.php#L82) for an example.
* *getRoles* - List bundle roles. The list must be an array with   
    * name: String, must start with ROLE_, e.g. ROLE_USER_ADMIN
    * title: String, human readable, e.g. "Can administrate users"
    * @return array roles. See [source](https://github.com/mapbender/mapbender/blob/release/3.0.6/src/Mapbender/ManagerBundle/MapbenderManagerBundle.php#L64) example.
    

### Create composer package

Create a [composer].json as described in the example.

Dont forget to fill it up:
* **authors** - Is required in order to know the technical director of the [modules]. 
* **name** - Unique name of the [module]. You can check the existens by [composer packagist](https://packagist.org/) service. 
* **license** - [license] short name.
* **description** - Describes the [module].
* **autoload** - [psr-4] Path to the namespace classes to load them correctly.
* **target-dir** - Path where [bundle] root should be placed in.

Better if **autoload** and **target-dir** will be copied from example as is, so only [bundle] names should be changed.

```sh
{
    "name": "mapbender/new-awesome-bundle",
    "description": "New awesome bundle description",
    "keywords": ["mapbender","awesome","geo"],
    "type": "library",
    "license": "MIT",
    "authors": [
        {"name": "Andriy Oblivantsev"}
    ],
    "require": {
        "php": ">=5.3.3",
        "imag/ldap": "2.x"
    },
    "autoload": {
		"psr-4": {"Mapbender\\NewAwesomeBundle": "."}
    },
    "target-dir": "Mapbender/NewAwesomeBundle",
    "extra": {
        "branch-alias": {
            "dev-master": "1.0-dev"
        }
    }
}
```

More about composer definition [here](https://getcomposer.org/doc/04-schema.md).

### Save bundle 

* Commit changes
* [Create](https://help.github.com/articles/create-a-repo/) [GitHub] repository 
* [Add remote](https://help.github.com/articles/adding-a-remote/) 
* [Push](https://help.github.com/articles/pushing-to-a-remote/)  changes to [GitHub]


### Versioning

To learn about semantic versioning please read the documentation [here][versioning].

#### Create version 

```sh
git tag 0.0.1
```

#### List versions

```sh 
git tag -l
```


#### Push version

```sh 
git push --tags
```


### Register bundle

[Switch](#switch-to-project-directory) to [mapbender] project directory.

Register new [git] [repository] as [composer] [bundle]/[module] in [composer].json.

Example: 

```json 
{
    "require-dev": {
        "mapbender/new-awesome-bundle": "dev-master"
    },
    "require": {
        "mapbender/new-awesome-bundle": "*"
    },
    "repositories": [
        {"type": "git","url": "https://github.com/mapbender/new-awesome-bundle.git"}
    ]
}
```


### Update composer after add new module

```sh
../composer.phar update -o
```



### Switch to module directory

```sh
cd vendor/mapbender/new-awesome-bundle/Mapbender/NewAwesomeBundle/
```

This is a normal [git] repository, [bundle] and [composer] package at the same time. 

Now you are ready to change and commit code directly in the project. 

To get involved, please look at [digitizer] structure as example.


# Submodules

~~Nice ability to get bundles and modules linked with each other!~~

**Please stop develop this way!** 

This workflow is deprecated.

This approach has been used a long time to develop and distribute [Mapbender], 
but due to the course complexity and many changes in diverse [bundles], located in different sub-modules, 
without [versioning], we decided to change the development workflow to [composer] packages named as [modules].



# Elements

## Definition

Mapbender elements are an optional part of each [bundle] and should be stored under *SomeBundle/SomeElementName* folder.


Each Mapbender element is:

* A central part of Mapbenders configurable functionality
* [Symfony] controller([API]) 
* [jQuery] [widget]
* Part of [bundle]
* Child of [Element] class


Each Mapbender element has its own:

* JavaScript front end [jQuery] [widget]
* HTML [DOM] element
* [translation]/s as [TWIG] file
* [SCSS]/[CSS] style(s)
* [Backend] [API] 
* administration form type to set, store and restore configuration

## Creation

Generate a new element by giving:

 * name of [bundle]
 * name of new [element]
 * source directory, relative to _application_ folder, where the [bundle] is stored 

```sh
app/console mapbender:generate:element "Mapbender\DigitizerBundle" MyNewElement vendor/mapbender/digitizer
```

Now there are new files located in the [bundle] folder. For more information read the [full tutorial](http://doc.mapbender3.org/en/book/development/element_generate.html).

In order to introduce our new element and to show it by adding a new element, it should be registered in the main [bundle] file in "getElements" method, 
located in the root folder of the [bundle].

### Example:
 * Bundle file: Mapbender/DigitizerBundle/MapbenderDigitizerBundle.php
 
```php
...
class MapbenderDigitizerBundle extends MapbenderBundle
{
    public function getElements()
    {
        return array(
            'Mapbender\DigitizerBundle\Element\MyNewElement'
        );
    }
}
```


# Templates

* **Fullscreen** - is the main template. This should be used for a desktop 
based application.

* **Mapbender mobile template** - is the current mobile template. This is in development
and can be used for simple tasks. Use it at your own risk.

* **Classic template** - is deprecated. This template shouldn't be used. The only reason why it's still in the list is for 
backwards capability of Mapbender 3.0.x based projects.

* **Responsive** - isn't ready and shouldn't be used. This template is just a 
playground for future development and for new templates. Use it at your own risk.


## Styling

Application template styling can be done by using the [CSS] tab in the backend for adding your own style sheets.

[CSS]/[SCSS] text will be parsed to use on top of the application it's stored for.

## Creation 

A template is a part of the [bundle]. It's located in the  "Templates/" directory. 

* Create new template PHP-Class in "Template" directory
* Extend Mapbender template by: 
    * "Mapbender/CoreBundle/Component/Fullscreen" for desktop application
    * "Mapbender/MobileBundle/Template/Mobile" for mobile application

Example:

```php
class NewTemplate extends Mapbender\CoreBundle\Component\Template{
}
```

* override public methods pass your needs
* register template in [bundle] register file "AcmeBundle.php", this is located in bundle root folder

```php
    public function getTemplates()
    {
        return array('Mapbender\AcmeBundle\Template\NewTemplate');
    }
```

* remove the cache

Now your template should be avaible. You can use it by creating a new application and choose it in the template list.

# Translations

Read more about [translations](http://symfony.com/doc/2.3/book/translation.html).

To get unique named translations, use a bundle name prefix before subject.

## Example

```xml
      <trans-unit id="9728e3887eb78b1169723e59697f00b9" resname="somebundle.dialog.button.add">
        <source>somebundle.dialog.button.add</source>
        <target>Add</target>
      </trans-unit>
```

## Generate translations

By using [TWIG] files, a implemented generator can transform any used [translation] automatically in 'xlf' files.

Therefore, these few parameters must be submitted:

* **--output-format=** - Format of generated translation file. It's important to use [xlf]. 
* **--force** - Force append new translations to existing translation files
* **Language** - Language short name (de/en/ru)
* **BundleName** - Name of [bundle]

### Example

```sh
app/console translation:update --output-format=xlf --force de MapbenderCoreBundle
```


## Feature branch

It's mandatory to use the "feature/" prefix in the branch name.

Example:


* Create branch

```sh
cd mapbender
git checkout -b "feature/mega-cool-feature-x"
``` 
* Improve the code
* Save changes 

```sh
git add *
git commit -m "Add some new stuff"
``` 
* Merge current release code

```sh
git fetch -a
git merge "release/3.0.6"
``` 
* If conflicts arise, resolve [them][Resolve git conflicts]
* Run tests
* Push the changes on [github]

```sh
git push
``` 
* Create [pull-request]

Then please wait for the feedback. We will check it out and review your code to merge it in the branch.

## Bug fix branch

It's mandatory to use "hotfix/" prefix in your branch name.

Example:

* Create branch

```sh
cd mapbender
git checkout -b "hotfix/bug-short-description"
``` 
* Improve the code
* Save changes 

```sh
git add *
git commit -m "Fix bug description"
``` 
* Merge current release code

```sh
git fetch -a
git merge "release/3.0.6"
``` 
* If conflicts arise, resolve [them][Resolve git conflicts]
* Run or add new tests relevant to the fixed bug 
* Push the changes on [github]

```sh
git push
``` 
* Create [pull-request] on the current release branch

Then please wait for the feedback. We will check it out, test and review your code to merge it in the branch.

## Release branch

This branch can only be changed by a project maintainer.
It's mandatory to use *release/* prefix in your branch name.

Example:

* Checkout release branch

```sh
cd mapbender
git checkout "release/3.0.6"
``` 
* Fetch changes  

```sh
git fetch -a
git pull
``` 
* Merge changes

```sh
git merge "hotfix/bug-short-description"
``` 
* If conflicts arise, resolve [them][Resolve git conflicts]
* Run or add new tests relevant to the new feature
* Code review
* Run tests
* Save changes

```sh
git commit -m "Merge 'hotfix/bug-short-description'"
``` 
* Push on [github]

```sh
git push
``` 



# Tests

Don't forget to write tests!
Moreover, please write a clear commit message.
Here are some good explanations:

## Examples


* Test all [bundles]
```bash
bin/phpunit -c app vendor/mapbender
```

* Test unique [bundle]
```bash
bin/phpunit -c app vendor/mapbender/digitizer
```

* Test [bundle] class
```bash
bin/phpunit -c app vendor/mapbender/digitizer/Mapbender/DigitizerBundle/Tests/FeaturesTest.php
```

# Resources

## Modules

* [Mapbender] - Contains Core, Manager, Print, Mobile and some other [bundles] this will be extracted as [modules] in next releases.
* [FOM] - **F**riends **o**f **M**apbender contains Administration and Security components [bundles]. The module is deprecated and will be split in new modules as optional parts of Mapbender3.
* [OWS Proxy] - Secure communicate remote hosts through Mapbender3 backend.
* [Digitizer] - Digitalizing [bundle], which contains geometry [services].
* [DataStore] - DataStore [bundle], which contains data drivers and [services].

## Libraries
* [Symfony framework]
* [Composer documentation](https://getcomposer.org/doc/)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)

[rules]: #rules "Rules"
[rule]: #rules "Rules"
[bundle]: #bundles "Bundle"
[bundles]: #bundles "Bundle"
[test]: #tests "Tests"
[tests]: #tests "Tests"
[features]: #features
[feature]: #features
[elements]: #elements
[element]: #elements
[templates]: #templates
[template]: #templates
[translation]: #translations
[translations]: #translations
[modules]: #modules
[module]: #modules
[submodules]: #submodules "Git submodules"
[manager controllers]: #manager-controllers 
[layers]: #layers
[start webserver]: #start-web-server "Start webserver"
[services]: http://symfony.com/doc/2.3/book/service_container.html "Symfony Services"
[components]: http://symfony.com/doc/current/components/index.html
[style guide]: http://www.php-fig.org/psr/psr-2/
[Symfony]: http://www.symfony.com "Symfony framework"
[Symfony framework]: http://www.symfony.com "Symfony framework"
[Composer]: https://getcomposer.org/doc/
[Composer]: https://getcomposer.org/doc/00-intro.md "Composer"
[git]: https://git-scm.com/ "Git"
[repository]: https://git-scm.com/book/en/v2/Git-Basics-Getting-a-Git-Repository "Git repository"
[API]: https://en.wikipedia.org/wiki/Application_programming_interface
[jQuery]: https://jquery.com/
[widget]: http://github.bililite.com/understanding-widgets.html
[license]: https://getcomposer.org/doc/04-schema.md#license
[README]: https://en.wikipedia.org/wiki/README
[CONTRIBUTING]: https://github.com/blog/1184-contributing-guidelines
[MD]: https://guides.github.com/features/mastering-markdown/ "Markdown"
[PUML]: http://plantuml.com/ "PlaintUML"
[DOM]: "http://www.w3schools.com/js/js_htmldom.asp" "HTML DOM"
[SCSS]: http://sass-lang.com/guide "SCSS"
[CSS]: http://www.w3schools.com/css/css_intro.asp "CSS"
[TWIG]: http://twig.sensiolabs.org/ "TWIG"
[parameters.yml]: http://symfony.com/doc/current/best_practices/configuration.html "Symfony configuratioN"
[pull-request]: https://help.github.com/articles/creating-a-pull-request/ "Pull requests"
[Resolve git conflicts]: https://help.github.com/articles/resolving-a-merge-conflict-on-github/ "Resolve git conflicts"
[branch]: https://help.github.com/articles/about-branches/ "Branching"
[submodule]: https://git-scm.com/book/en/v2/Git-Tools-Submodules  "Git submodule"
[Mapbender]: https://mapbender3.org/  "Mapbender3"
[FOM]: https://github.com/mapbender/fom  "FOM submodule"
[OWS Proxy]: https://github.com/mapbender/owsproxy3  "OWS proxy submodule"
[Digitizer]: https://github.com/mapbender/mapbender-digitizer "Mapbender digitizer module"
[DataStore]: https://github.com/mapbender/data-source "Mapbender data source"
[github]: https://github.com/ "GitHub"
[phpunit]: https://phpunit.de/getting-started.html "PHPUnit"
[versioning]: http://semver.org/
