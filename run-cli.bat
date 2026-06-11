@echo off

cd /d "%~dp0"

php-zts composer.phar dump-autoload
frankenphp php-cli system/index.php %*
