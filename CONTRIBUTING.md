# Developer Guide

This guide provides instructions (mostly as links) on how to build the repo 
and implement improvements. 
It will expand over time.

## Architecture

Mapbender is based on [Symfony framework](symfony) and uses composer to get external libraries and own bundle-modules.

## Installation  


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

Create database 
```sh
app/console doctrine:schema:create
```

Fill ACL
```sh
app/console doctrine:fixtures:load --acl
```

Create root user and set set password
```sh
app/console fom:user:resetroot --username root --password root --silent
```

Install composer libraries
```sh
composer install 
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

*Notice: this workflow is deprecated, 
This approach is used to develop and distribute Mapbender long time.
Course complexity and many changes in diverse bundles which located 
in diverse submodules was decided to change development workflow.* 

## Definition

[Submodule] - git repository, which is linked on the main "mapbender-starter" repository.
Git submodules is a part of git modularity, 

## Description

Now there are three submodules: [Mapbender][Mapbender], [FOM][FOM] and [OWS Proxy][OWS Proxy], located in ```application``` folder
Each [submodule] contains own bundles. 

## Branches
In order to change the source in [submodule] it's important to create a branch.

### Feature [brunch]

It's mandatory to use "feature/" prefix in brunch name.

Example:

* Create brunch
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

### Bug fix brunch

It's mandatory to use "hotfix/" prefix in brunch name.

Example:

* Create brunch
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
* Run tests
* Push the changes on [github]
```sh
git push
``` 
* Create [pull-request] on the current release branch

Wait for the answer. We will checkout and review you code, to get merge it in.

#### Release brunch

This branch can be changed only by project maintainer.
It's mandatory to use "release/" prefix in brunch name.

Example:

* Checkout release brunch
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

---

### Modules

#### Create Module 

It's important to agree and follow rules:

Each module is:

* Is git repository
* Is symfony bundle
* Is mapbender bundle
* Is composer library (composer.json)

Each module should have:

* identical structure
* own license file
* own README file, which describe the functionality and using
* own CONTRIBUTING to get know other developers how to contribute in it


### Bundles 

Bundle is a set of functionality, like a library, which ca be used outside of the bundle.
The goal of the Bundle is to restrict usage of global name space and switch, swap and extend separated functionality.
In the past development many bundle was a part of git submodules. Now the days each bundle should be 
own git repository and reuse the same directory structure to implement new things. 


#### Create Bundle 

In order to create bundle, please look an example structure at [digitizer].
There is


## Elements
## Create element

 mapbender:assets:dump                 Dump all Mapbender application assets.

```app/console mapbender:generate:element``` - Is deprecated method to generate one element, please don't use it.

  mapbender:generate:template           Generates a Mapbender application template
  mapbender:generate:translation        Generates a Mapbender translation


#### Testing


 Application function test
```bash
cd application 
vendor/phpunit/phpunit/phpunit -c app/phpunit.xml mapbender/src/Mapbender/ManagerBundle/Tests/ApplicationTest.php
```


### List application commands
```sh
app/console
```


### Unit tests

Write tests.
Follow our style guide.
Write a good commit message.


## Building Bundle

# Additional Resources

## Submodules

* [Mapbender] - Contains Core, Manager and Print [bundle]
* [FOM] - *F*riends *o*f *M*apbender submodule contains some additional bundles.
* [OWS Proxy] - OWS Proxy bundle.

## Modules
* [Digitizer] - Digitalizing bundle, which contains geometries services

## Libraries
* [Symfony framework](http://www.symfony.com)
* [PHPUnit documentation](https://phpunit.de/)
* [Composer documentation](https://getcomposer.org/doc/)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)

[pull-request]: https://help.github.com/send-pull-requests
[Mapbender]: https://github.com/mapbender/mapbender  "Mapbender submodule"
[FOM]: https://github.com/mapbender/fom  "FOM submodule"
[OWS Proxy]: https://github.com/mapbender/owsproxy3  "OWS proxy submodule"
[submodule]: https://git-scm.com/book/de/v1/Git-Tools-Submodule  "Git submodule"
[Digitizer]: https://github.com/mapbender/mapbender-digitizer "Mapbender digitizer module"
[Resolve git conflicts]: https://github.com/conflicts/resolve "Resolve git conflicts"