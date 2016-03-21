# Developer Guide

This guide provides instructions (mostly as links) on how to build the repo and implement improvements. 
It will expand over time.

## Architecture

Mapbender is based on Symfony framework and uses composer to get external libraries and own bundle-modules.

# How to contribute

Third-party patches are essential for keeping Mapbender great. We simply can't access the huge number of platforms and myriad configurations for running
puppet. We want to keep it as easy as possible to contribute changes that get things working in your environment. There are a few guidelines that we
need contributors to follow so that we can have a chance of keeping on top of things.

## Installation  

### Clone

Clone the project via SSH
```sh
git clone git@github.com:mapbender/mapbender-starter.git 
```

Alternative, is possible to clone the project via HTTP
```sh
git clone https://github.com/mapbender/mapbender-starter.git
```

### Switch to the project directory

```sh
cd mapbender-starter
```

### Clone submodules 
```sh
git submodule https://github.com/mapbender/mapbender.git --init --recursive 
```



## Sub modules vs Modules

### Modules

### Sub modules
Git submodules is a part of git modularity, which was used to develop distribute Mapbender long time.
Course of many changes in diverse bundles which located in diverse su-modules was decided to change development 
workflow. For more information please read [Modules]


### Bundles

Bundle is a set of functionality, like a library, which ca be used outside of the bundle.
The goal of the Bundle is to restrict usage of global name space and switch, swap and extend separated functionality.
In the past development many bundle was a part of git submodules. Now the days each bundle should be 
own git repository and reuse the same directory structure to implement new things. 
For a example, use look at [digitizer bundle], to look how the structure is.


#### Create Bundle 

In order to create bundle, please look an example structure

## Elements
## Create element



#### Testing





#### Mapbender
#### FOM 

#### OWS Proxy





Set up your machine:

./bin/setup
Make sure the tests pass:

rake
Make your change. Add tests for your change. Make the tests pass:

rake
Push to your fork and submit a pull request.

At this point you're waiting on us. We like to at least comment on pull requests within three business days (and, typically, one business day). We may suggest some changes or improvements or alternatives.

Some things that will increase the chance that your pull request is accepted:

Write tests.
Follow our style guide.
Write a good commit message.




 Application function test
```bash
cd application 
vendor/phpunit/phpunit/phpunit -c app/phpunit.xml mapbender/src/Mapbender/ManagerBundle/Tests/ApplicationTest.php
```



### Unit tests

## Building Bundle

# Additional Resources

* [Digitizer bundle](https://github.com/mapbender/mapbender-digitizer)
* [PHPUnit documentation](https://phpunit.de/)
* [Composer documentation](https://getcomposer.org/doc/)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)