@echo off
setlocal enabledelayedexpansion

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
echo [INFO] Using environment file: %ENV%

php-zts composer.phar dump-autoload

if %ERRORLEVEL% neq 0 (
    echo [ERROR] Composer dump-autoload failed.
    exit /b %ERRORLEVEL%
)

frankenphp run --config Caddyfile --envfile %ENV%
