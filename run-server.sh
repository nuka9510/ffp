#!/bin/bash

for i in "$@"; do
  case $i in
    --env=*)
    ENV="${i#*=}"
    shift
    ;;
  esac
done

php composer.phar dump-autoload
frankenphp run --config Caddyfile --envfile "${ENV:-.env}"