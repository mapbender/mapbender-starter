cd application
php bin/composer install -o
php bin/composer init-example
php app/console assets:install
php app/console server:run
