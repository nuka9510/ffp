#!/bin/bash

cd "$(dirname "$0")"

php-zts composer.phar dump-autoload
frankenphp php-cli system/index.php "$@"
