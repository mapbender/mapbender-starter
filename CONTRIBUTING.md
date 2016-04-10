# Developer Guide

Mapbender welcomes contributions from all members. You are welcome to join us in the development, if you consider the following guidelines.


# Architecture

Mapbender is based on [Symfony framework](symfony) and uses composer to get external libraries and own bundle-modules.

# Installation  

You can find the complete installation description here:
http://doc.mapbender3.org/mapbender-documentation/output/en/book/installation.html

Here is a quick installation guide to get git-based developer edition of mapbender:

Clone the project via SSH
```sh
git clone git@github.com:mapbender/mapbender-starter.git 
```

Alternatively, it is also possible to clone the project via HTTP
```sh
git clone https://github.com/mapbender/mapbender-starter.git
```

Switch to the project directory
```sh
cd mapbender-starter
```

Clone submodules 
```sh
git submodule update --init --recursive --force
```

Switch to the application directory
```sh
cd application
```

Copy ```parameters.yml``` and edit it for your system.
```sh
cp app/config/parameters.yml.dist app/config/parameters.yml
```

Create database and schema structures (tables, triggers, etc)
```sh
app/console doctrine:database:create
app/console doctrine:schema:create
```

Loads the applications from the "mapbender.yml" into a mapbender database
```sh
app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Application/ --append
```

Import EPSG codes
```sh
app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Epsg/ --append
```

Create root user and set the password
```sh
app/console fom:user:resetroot --username root --password root --silent
```

Install composer libraries
```sh
../composer/composer.phar update -o
```

Now yo are ready to use your mapbender.

# How to contribute

Third-party patches are essential for the preservation of high standards in Mapbender.
We simply can't access the huge number of platforms and myriad configurations 
for running Mapbender. 
We want it as easy as possible to carry out changes to get the modules in your environment to run. 
There are a few guidelines that we need contributors to follow so that 
we can have a chance of keeping on top of things.

# Submodules


*Notice: this workflow is deprecated.*

*This approach has been used a long time to develop and distribute Mapbender.
Due to the course complexity and many changes in diverse bundles, located in different sub-modules, 
it was decided to adapt the development workflow in the next release of [Mapbender].*

## Definition

[Submodule] - git repository, which is linked on the main "mapbender-starter" repository.
Git submodules is a part of git modularity, 

## Description

For now there are three submodules: [Mapbender], [FOM] and [OWS Proxy], located in ```application``` folder
Each [submodule] contains one or many bundles. 

## Branches
In order to change the [submodule] source code it is important to create a new [branch]. 

### Feature branch

It's mandatory to use "feature/" prefix in the branch name.

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
git merge "release/3.0.5"
``` 
* If conflicts arise, resolve [them][Resolve git conflicts]
* Run tests
* Push the changes on [github]
```sh
git push
``` 
* Create [pull-request]

Then please wait for the feedback. We will check it out and review your code to merge it in the branch.

### Bug fix branch

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
git merge "release/3.0.5"
``` 
* If conflicts arise, resolve [them][Resolve git conflicts]
* Run or add new tests relevant to the fixed bug 
* Push the changes on [github]
```sh
git push
``` 
* Create [pull-request] on the current release branch

Then please wait for the feedback. We will check it out, test and review your code to merge it in the branch.

### Release branch

This branch can only be changed by project maintainer.
It's mandatory to use *release/* prefix in your branch name.

Example:

* Checkout release branch
```sh
cd mapbender
git checkout "release/3.0.5"
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


# Modules

Module is a new part of mapbender concept, based on [Symfony modularity rules](http://www.symfony.com) 
and [composer] dependency manager. 

Special builds can be created that exclude subsets of Mapbender functionality. 
This allows for smaller custom builds when the builder is certain 
that those parts of Mapbender are not being used. 
For example, an application that only use map view and did not need [Digitizer] functionality.

In the future release any module may be excluded except for core. 

In the past development bundles was a part of git [submodules]. 
Now the days each module should be own git repository 
and reuse the same directory structure. 

## Rules

It's _important_ to follow the rules:

Each module is:

* Is [git] repository
* Is [Symfony] [bundle]
* Is mapbender [bundle]
* Is [composer] library (has [composer] definition)

Each module should have:

* only one [bundle]
* only one primary namespace 
* identical structure
* own [license] file
* own function description [README.md] file
* own [CONTRIBUTING.md] describes how other developers should install, setup and contribute in it
* own [tests] relevant to new [features], [elements] or functionality

## Bundles 

Bundle is a set of functionality, synonym to library, which can be created and used outside of the Mapbender.
The goal of the Bundle is to restrict usage of global name space and switch or swap extend separated functionality.

### Bundle structure
 
Is a special set of folders and files that look so:

* *Component* - Contains _components_ in other words _services_, 
    this contains buisness logic in classes. The _components_ are used by API's-
* *Exception* - Contains exceptions.
* *DataFixtures* - Fixtures are used to load a controlled set of data into a database. This data can be used for testing or could be the initial data required for the application to run smoothly
* *EventListener* - Contains event listeners
* *DependencyInjection* - Contains only one file, this makes in _magical_ way [components] available as [services], 
    if they _registred_ in _Resources/config/services.xml_ [bundle] folder
* *Documents* - Contains documents related to the [bundle]. [MD] for text and [PUML] for charts formats are preferred.
* *Element* - Contains Mapbender [elements]. This folder isn't [symfony] conform.

### Create Bundle (Module)

* Create [git] repository outside of Mapbender, as own project
* Create [composer].json
* Create [bundle structure](#Bundle%20structure)
* Follow module [rules]

To get involved, please look at [digitizer] structure as example.

## Elements

### Definition

Elements can be a part of each [bundle]
and should be stored in *SomeBundle/SomeElementName* folder.

Each Mapbender element is:

* central part of Mapbender configurable functionality.
* [Symfony] controller([API]) 
* [jQuery] [widget]
* Part of [bundle]
* Child of [Element] class

Each Mapbender element has own:

* JavaScript front end [jQuery] [widget]
* HTML [DOM] element
* [translation](s) as [TWIG] file
* [SCSS]/[CSS] style(s)
* [Backend] [API] 
* administration form type to set, store and restore configuration


## Creation

## Templates

There is very deprecated templates named "Classic template", this one 
should be never used. The only reason why it's still in the list is 
backwards capability to Mapbender 3.0.x based projects.

*Responsive* isn't ready and should be not used. This template is just a 
background for future development or as base for new template. Use it 
at your own risk.

*Fullscreen* - is the main template. This should be used for desktop 
based application.

*Mabender mobile template* - is the current template this is in development
and can be used for simple task. Use at own risk.


### Styling

Application template styling can be done by using "CSS" tab by editing.
By save CSS/SCSS text will be parsed ann

## Tests

Don't forget to write tests!
Follow our [style guide].
Write a good commit message.

 Application function test
```bash
cd application 
bin/phpunit -c app mapbender/src/Mapbender/ManagerBundle/Tests/ApplicationTest.php
```

# Resources

## Submodules

* [Mapbender] - Contains Core, Manager and Print [bundles]
* [FOM] - *F*riends *o*f *M*apbender [submodule] contains some additional [bundles].
* [OWS Proxy] - OWS Proxy bundle.

## Modules

* [Digitizer] - Digitalizing [bundle], which contains geometries [services]
* [DataStore] - DataStore [bundle] contains data drivers and [services]

## Libraries
* [Symfony framework]
* [PHPUnit documentation](https://phpunit.de/)
* [Composer documentation](https://getcomposer.org/doc/)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)

[rules]: #rules "Rules"
[rule]: #rules "Rules"
[bundle]: #bundle "Bundle"
[bundles]: #bundle "Bundle"
[test]: #tests "Tests"
[tests]: #tests "Tests"
[features]: #features
[feature]: #features
[elements]: #element
[element]: #element
[services]: #services "Symfony Services"
[components]: #element
[style guide]: #element

[Symfony]: http://www.symfony.com "Symfony framework"
[Symfony framework]: http://www.symfony.com "Symfony framework"
[Composer]: https://getcomposer.org/doc/
[Composer]: https://getcomposer.org/doc/00-intro.md "Composer"
[git]: https://git-scm.com/ "Git"
[API]: #API
[jQuery]: #jQuery
[widget]: #Widget
[license]: #license
[README.md]: #readme
[CONTRIBUTING.md]: #contributing
[MD]: #markdown
[PUML]: #PlaintUML
[DOM]: #dom
[SCSS]: #scss
[CSS]: #scss
[TWIG]: #scss
[translation]: #translation


[Digitizer]: https://github.com/mapbender/mapbender-digitizer "Mapbender digitizer module"
[DataStore]: https://github.com/mapbender/data-source "Mapbender data source"

[pull-request]: https://help.github.com/send-pull-requests "Pull requests"
[Resolve git conflicts]: https://github.com/conflicts/resolve "Resolve git conflicts"
[branch]: https://help.github.com/branch "Branching"

[submodule]: https://git-scm.com/book/de/v1/Git-Tools-Submodule  "Git submodule"
[Mapbender]: https://github.com/mapbender/mapbender  "Mapbender submodule"
[FOM]: https://github.com/mapbender/fom  "FOM submodule"
[OWS Proxy]: https://github.com/mapbender/owsproxy3  "OWS proxy submodule"
