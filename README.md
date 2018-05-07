# Mapbender

Mapbender is a web based geoportal framework.

The [official site](http://mapbender.org/?q=en) contains [documentation](http://mapbender.org/?q=en/documentation) and [installation information](http://doc.mapbender.org/en/book/installation.html) ([also available in German](http://doc.mapbender.org/de/book/installation.html)).

To install Mapbender from this Git-repository, please read the guide of the [Git-based installation](http://doc.mapbender.org/en/book/installation/installation_git.html) ([in German](http://doc.mapbender.org/de/book/installation/installation_git.html)).

## Requirements

At a minimum, Mapbender requires OpenSSL, curl, a PHP 5.4 interpreter, bzip2 decompression and the following php extensions to be installed and enabled:
* curl
* gd
* intl
* mbstring
* bz2
* xml
* json
* sqlite

You may have to install and enable further extensions at your own discretion if you
want to use specific database systems.

We also recommend installing an sqlite client so you can inspect the (default) sqlite
database.

E.g.

### Ubuntu 16.04

```sh
sudo apt-get install php php-cli openssl bzip2 \
    php-curl php-gd php-intl php-mbstring \
    php-bz2 php-xml php-json \
    php-sqlite php-pgsql php-mysql \
    sqlite curl
```


### Ubuntu 14.04

14.04 is similar, but requires activation of the "universe" repository and uses versioned package names ("php5-" instead of "php-").

Activate universe repository:

```sh
sudo add-apt-repository universe
```

Update package list:

```sh
sudo apt-get update
```

Install packages:

```sh
sudo apt-get install php5 php5-cli openssl bzip2 \
    php5-curl php5-gd php5-intl php5-mbstring \
    php5-xml php5-json \
    php5-sqlite php5-pgsql php5-mysql
    sqlite curl
```

## Getting the code

Git clone mapbender-starter via ssh or https at your preference:
```sh
git clone git@github.com:mapbender/mapbender-starter.git mapbender-starter
```

or

```sh
git clone https://github.com/mapbender/mapbender-starter.git mapbender-starter
```

## Bootstrapping
Switch to project directory and run ./bootstrap
```sh
cd mapbender-starter
./bootstrap
```

The bootstrap command performs the following required setup tasks for you:
* installs userland dependencies (via composer)
* creates a parameters.yml by copying the bundled parameters.yml.dist
* performs the necessary database setup (as an sqlite file in `application/app/db/demo.sqlite`)
* creates a root account with a default password `root` (which you should change later)

It then continues booting into PHP's development web server, which is not
production quality, but allows quick testing. The URL is shown in the output:
```sh
Server running on http://localhost:8000
```

We recommend doing this at least once even if you plan on using a proper webserver.
You can stop the server with a simple Ctrl+C.

The full setup processes is only needed once. If you want to run the development webserver again,
you can skip the dependency checks etc and directly use:
```sh
app/console server:run
```

## Components

Our code is maintained using git and hosted at Github. We split up our code into several parts:

1. mapbender-starter: The starter project you are using right now. This provides a complete application to play with and build upon.
2. [mapbender](https://github.com/mapbender/mapbender/tree/release/3.0.6): The CoreBundle contains all basic functionality, including base classes and interfaces for the Mapbender API usable by Mapbender and third-party bundles.
3. [FOM](https://github.com/mapbender/fom/tree/release/3.0.6): User and rights management.
4. [OWSProxy3](https://github.com/mapbender/owsproxy3/tree/release/3.0.6): OWSProxy3 is a transparent Buzz-based proxy that uses cURL for connection to web resources via/without a proxy server.
5. mapquery: Mapbender uses MapQuery as its jQuery/OpenLayers wrapper. We maintain our own clone.


## Issues

Please report issues at the [Mapbender repository here at Github](https://github.com/mapbender/mapbender/issues).


## Other downloads

Pre-built Tarballs and Zip files (where all subbodules and Symfony bundles are integrated) are available at our [Download page](http://mapbender.org/download).


## Mapbender demo & sandbox

Wanna see Mapbender live? A demo installation is available at http://demo.mapbender.org/.


## Follow us on Twitter

You can follow Mapbender at [Twitter](https://twitter.com/mapbender).
