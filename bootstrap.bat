cd application
php bin/composer install -o --no-scripts --no-suggest
php bin/composer init-example
php bin/console assets:install
php bin/console mapbender:database:init -v
php bin/composer run post-install-cmd
echo Bootstrap finished!
echo If you want to run the builtin development server, run:
echo cd application
echo php bin/console server:run
