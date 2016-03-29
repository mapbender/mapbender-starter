# Developer Guide

Mapbender welcomes contribution from everyone. Here are the guidelines if you are thinking of helping us.


## Architecture

Mapbender is based on [Symfony framework](symfony) and uses composer to get external libraries and own bundle-modules.

## Installation  

Complete installation description you will find here:
http://doc.mapbender3.org/mapbender-documentation/output/en/book/installation.html

Here is a quick installation guide to get git-based developer edition of mapbender:

Clone the project via SSH
```sh
git clone git@github.com:mapbender/mapbender-starter.git 
```

Alternative, is possible to clone the project via HTTP
```sh
git clone https://github.com/mapbender/mapbender-starter.git
```

Switch to the project directory
```sh
cd mapbender-starter
```

Clone submodules 
```sh
git submodule https://github.com/mapbender/mapbender.git --init --recursive --force
```

Switch to the application directory
```sh
cd application
```

Copy ```parameters.yml``` and edit to fit you system.
```sh
cp app/config/parameters.yml.dist app/config/parameters.yml
```

Create database and schema structures(tables, triggers, etc)
```sh
app/console doctrine:database:create
app/console doctrine:schema:create
```

Import EPSG codes
```sh
app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Epsg/ --append
app/console doctrine:fixtures:load --fixtures=./mapbender/src/Mapbender/CoreBundle/DataFixtures/ORM/Application/ --append
```

Create root user and set the password
```sh
app/console fom:user:resetroot --username root --password root --silent
```

Install composer libraries
```sh
../composer/composer.phar install 
```

Now yo are ready.

# How to contribute

Third-party patches are essential for keeping Mapbender great. 
We simply can't access the huge number of platforms and myriad configurations 
for running Mapbender. We want to keep it as easy as possible to contribute 
changes that get things working in your environment. 
There are a few guidelines that we need contributors to follow so that 
we can have a chance of keeping on top of things.

# Submodules


*Notice: this workflow is deprecated.*

*This approach is used to develop and distribute Mapbender long time.
Course complexity and many changes in diverse bundles which located 
in diverse submodules was decided to change development workflow in the next release of [Mapbender].*

## Definition

[Submodule] - git repository, which is linked on the main "mapbender-starter" repository.
Git submodules is a part of git modularity, 

## Description

Now there are three submodules: [Mapbender], [FOM] and [OWS Proxy], located in ```application``` folder
Each [submodule] contains one or many bundles. 

## Branches
In order to change the [submodule] source code it's important to create a [branch]. 

### Feature branch

It's mandatory to use "feature/" prefix in branch name.
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
* If the conflicts, resolve [them][Resolve git conflicts]
* Run tests
* Push the changes on [github]
```sh
git push
``` 
* Create [pull-request]

Wait for the answer. We will checkout and review you code, to get merge it in.

### Bug fix branch

It's mandatory to use "hotfix/" prefix in branch name.

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
* If the conflicts, resolve [them][Resolve git conflicts]
* Add tests relevant to the fixed bug 
* Push the changes on [github]
```sh
git push
``` 
* Create [pull-request] on the current release branch

Wait for the feedback. We will checkout, test and review you code, to get merge it in.

### Release branch

This branch can be changed only by project maintainer.
It's mandatory to use *release/* prefix in branch name.

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
* If the conflicts, resolve [them][Resolve git conflicts]
* Add tests relevant to the new feature
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


## Modules

Special builds can be created that exclude subsets of Mapbender functionality. 
This allows for smaller custom builds when the builder is certain 
that those parts of Mapbender are not being used. 
For example, an app that only used map view and did not need [Digitizer] functionality.

In the future release any module may be excluded except for core. 

In the past development bundles was a part of git [submodules]. 
Now the days each module should be own git repository 
and reuse the same directory structure. 

### Rules

It's _important_ to follow the rules:

Each module is:

* Is git repository
* Is symfony bundle
* Is mapbender bundle
* Is composer library (has composer definition)

Each module should have:

* only one bundle 
* only one primary namespace 
* identical structure
* own license file
* own function description README.md file
* own CONTRIBUTING.md to get know other developers how to contribute in it
* own tests relevant to new features, elements or functionality

## Bundles 

Bundle is a set of functionality, like a library, which can be used outside of the bundle.
The goal of the Bundle is to restrict usage of global name space and switch or swap extend separated functionality.


#### Create Bundle 

* Create git repository outside of Mapbender, as own project
* Create composer.json 
* Create bundle 
* Follow module [rules]

To get involved, please look at [digitizer] structure as example.

## Elements

### Definition

Every Element must have an HTML element it is represented by. 
In the most basic case, this can be a simple DIV element, but this can be complex as needed.
For HTML generation Mapbender3 uses Twig. A minimal twig template for an element would like this:

Mapbender Element is:

* Symfony controller(API) 
* jQuery widget
* Part of bundle

Mapbender Element has own:

* SCSS/CSS style(s)
* translation(s) as TWIG file
* JavaScript front end jQuery widget
* administration form type to set, store and restore configuration  



## Creation






## Tests

Write tests!
Follow our style guide.
Write a good commit message.

 Application function test
```bash
cd application 
vendor/phpunit/phpunit/phpunit -c app/phpunit.xml mapbender/src/Mapbender/ManagerBundle/Tests/ApplicationTest.php
```

# Resources

## Submodules

* [Mapbender] - Contains Core, Manager and Print [bundle]
* [FOM] - *F*riends *o*f *M*apbender submodule contains some additional bundles.
* [OWS Proxy] - OWS Proxy bundle.

## Modules

* [Digitizer] - Digitalizing bundle, which contains geometries services
* [DataStore] - DataStore bundle, which contains geometries services

## Libraries
* [Symfony framework](http://www.symfony.com)
* [PHPUnit documentation](https://phpunit.de/)
* [Composer documentation](https://getcomposer.org/doc/)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)


[pull-request]: https://help.github.com/send-pull-requests "Pull requests"
[Resolve git conflicts]: https://github.com/conflicts/resolve "Resolve git conflicts"
[branch]: https://help.github.com/branch "Branching"

[submodule]: https://git-scm.com/book/de/v1/Git-Tools-Submodule  "Git submodule"
[Mapbender]: https://github.com/mapbender/mapbender  "Mapbender submodule"
[FOM]: https://github.com/mapbender/fom  "FOM submodule"
[OWS Proxy]: https://github.com/mapbender/owsproxy3  "OWS proxy submodule"
[rules]: #rules "rules"

[Digitizer]: https://github.com/mapbender/mapbender-digitizer "Mapbender digitizer module"
[DataStore]: https://github.com/mapbender/data-source "Mapbender data source"