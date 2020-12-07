![Mapbender](./application/app/Resources/public/image/Mapbender-logo.png)

Mapbender is a web based geoportal framework.

[Official site](https://mapbender.org/?q=en) | [Live demo](https://demo.mapbender.org/) | [News on Twitter](https://twitter.com/mapbender)

For detailed usage information, including installation and integration topics, please see [official documentation](https://doc.mapbender.org/en/) ([also available in German](https://doc.mapbender.org/de/)).

## Requirements

Mapbender requires PHP 7.1, OpenSSL, curl, bzip2 decompression and the following php extensions:
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
* creates a parameters.yml by copying the bundled parameters.yml.dist
* performs the necessary database setup (as an sqlite file in `application/app/db/demo.sqlite`)
* creates a root account with a default password `root` (which you should change later)

The full setup processes is only needed once. If you invoke it again, nothing of consequence will happen.

## Built-in server
You can test your freshly bootstrapped Mapbender installation using a built-in development server.
This is not production quality, and has some known issues processing external requests (such as
in printing), but it allows some quick testing before you set up a production-grade web server.

The server is started like this:
```sh
cd application
app/console server:run
```

The URL is shown in the output:
```sh
Server running on http://localhost:8000
```

## Changing root account password
From the application directory run:
```sh
app/console fom:user:resetroot
```

## Issues

Please report issues [on Github](https://github.com/mapbender/mapbender/issues).


## Other downloads

Pre-packaged archives bundling all code dependencies are available at our [download page](https://mapbender.org/en/download).

