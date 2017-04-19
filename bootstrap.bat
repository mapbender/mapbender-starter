cd application
php ../composer.phar install -o
php ../composer.phar init-example
php app/console assets:install
php app/console server:run
