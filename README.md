![Mapbender](application/public/image/Mapbender-logo.png)

Mapbender is a web based geoportal framework.

[Official site](https://mapbender.org/?q=en) | [Live demo](https://demo.mapbender.org/) | [News on Twitter](https://twitter.com/mapbender)

[![DOI](https://zenodo.org/badge/DOI/10.5281/zenodo.5887014.svg)](https://doi.org/10.5281/zenodo.5887014)
![Packagist License](https://img.shields.io/packagist/l/mapbender/mapbender)


For detailed usage information, including installation and integration topics, please see [official documentation](https://doc.mapbender.org/en/) ([also available in German](https://doc.mapbender.org/de/)).

## Requirements

Mapbender requires PHP 8.0, OpenSSL, curl, bzip2 decompression and the following php extensions:
* zip
* curl
* gd
* intl
* mbstring
* bz2
* xml
* json
* sqlite3
* ldap

You may have to install and enable further extensions at your own discretion if you
want to use specific database systems.

We also recommend installing an sqlite client so you can inspect the (default) sqlite
database.

E.g.

### Ubuntu / Debian

```sh
sudo apt-get install php php-cli openssl bzip2 \
    php-curl php-gd php-intl php-mbstring \
    php-zip php-bz2 php-xml php-json \
    php-sqlite3 php-pgsql php-mysql php-ldap \
    sqlite3 curl
```

## System configuration
Some portions of Mapbender require correctly configured, writable PHP temporary directories
`sys_temp_dir` and `upload_tmp_dir`.

Your system most likely has separate php.ini files for cli and web server SAPIs such as mod_php, php-fpm, fcgi etc.
Make sure to make changes in _all_ php.ini files relevant to your installation.

## Getting the code

Git clone mapbender-starter via https or ssh (requires configured git credentials) at your preference:
```sh
git clone https://github.com/mapbender/mapbender-starter.git mapbender-starter
```

or

```sh
git clone git@github.com:mapbender/mapbender-starter.git mapbender-starter
```

## Bootstrapping
Switch to project directory and run ./bootstrap
```sh
cd mapbender-starter
./bootstrap
```

The bootstrap command performs the following required setup tasks for you:
* installs userland dependencies (via composer)
* creates a parameters.yaml by copying the bundled parameters.yml.dist
* creates a .env.local file by copying the bundled .env.local.dist
* performs the necessary database setup (as an sqlite file in `application/var/db/demo.sqlite`)
* creates a root account with a default password `root` (which you should change later)

The full setup processes is only needed once. If you invoke it again, nothing of consequence will happen.

## Built-in server
You can test your freshly bootstrapped Mapbender installation using symfony's development server.
This is not production quality, and has some known issues processing external requests (such as
in printing), but it allows some quick testing before you set up a production-grade web server.

To run the server you need to install the [Symfony CLI](https://symfony.com/download) first.

Then, the server is started like this:
```sh
cd application
symfony server:start --no-tls
```

The URL is shown in the output:
```sh
 [OK] Web server listening                                                                                              
      The Web server is using PHP CLI 8.2.10                                                                            
      http://127.0.0.1:8001      
```

## Environments
Two environments are available:

- `dev` shows full error messages including stack traces in the browser and enables the symfony debug console and profiler. 
  Also, caching is disabled.
- `prod` enables caching and shows only generic error messages. Error messages are written to logfiles.

The environment can be set via the APP_ENV environment variable. The default is `dev`. Make sure to change this when deploying
your application in the internet. The value can be changed in several ways:

- by editing `APP_ENV` in the `.env` file
- by overriding the value in a `.env.local` file
- by explicitly setting it when starting the local webserver: `APP_ENV=prod symfony server:start --no-tls`
- by setting an environment variable in your Apache2 vHost configuration: `SetEnv APP_ENV prod`

The `index_dev.php` file allows direct access to the dev environment. It can only be accessed from local 
ip addresses by default for security reasons.

## Changing root account password
From the application directory run:
```sh
bin/console fom:user:resetroot
```

## Issues

Please report issues [on Github](https://github.com/mapbender/mapbender/issues).


## Other downloads

Pre-packaged archives bundling all code dependencies are available at our [download page](https://mapbender.org/en/download).

## Other versions

| Mapbender release line | PHP versions  | Bundled Symfony version | Bundled composer version |
|------------------------|---------------|-------------------------|--------------------------|
| 3.0.8 (end of life)    | >=5.5, <=7.2  | 2.8LTS (end of life)    | 1.6.x                    |
| 3.2  (end of life)     | \>=7.1, <=7.4 | 3.4LTS (end of life)    | 1.10.x                   |
| 3.3                    | \>=7.4        | 4.4LTS (end of life)    | 2.1.x                    |
| 4.0                    | \>=8.0        | 5.4LTS                  | 2.6.x                    |
