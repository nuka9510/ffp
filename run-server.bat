@echo off
setlocal enabledelayedexpansion

cd /d "%~dp0"

set "ENV=.env"

:parse
if "%~1"=="" goto run
set "arg=%~1"
if "%arg:~0,6%"=="--env=" (
    set "ENV=%arg:~6%"
)
shift
goto parse

:run

php-zts composer.phar dump-autoload
frankenphp run --config Caddyfile --envfile %ENV%