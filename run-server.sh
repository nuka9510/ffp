#!/bin/bash

cd "$(dirname "$0")"

for i in "$@"; do
  case $i in
    --env=*)
    ENV="${i#*=}"
    shift
    ;;
  esac
done

php-zts composer.phar dump-autoload
frankenphp run --config Caddyfile --envfile "${ENV:-.env}"