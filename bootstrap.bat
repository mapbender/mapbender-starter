cd application
php bin/composer install -o --no-scripts --no-suggest
php bin/composer init-example
php bin/console assets:install
php bin/console mapbender:database:init -v
findstr /B /C:"JWT_PASSPHRASE=" .env.local >nul 2>&1
if errorlevel 1 (
    powershell -Command "[System.IO.File]::AppendAllText('.env.local', 'JWT_PASSPHRASE=' + [convert]::ToBase64String((1..32 | ForEach-Object {Get-Random -Minimum 0 -Maximum 256})) + [Environment]::NewLine)"
)
php bin/console lexik:jwt:generate-keypair
php bin/composer run post-install-cmd
echo Bootstrap finished!
echo If you want to run the builtin development server, install Symfony CLI from https://symfony.com/download then run:
echo cd application
echo symfony server:start --no-tls
