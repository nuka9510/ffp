FROM dunglas/frankenphp
WORKDIR /app
COPY . .
RUN install-php-extensions pdo_mysql gd intl zip opcache
RUN php-zts composer.phar install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist
ENTRYPOINT ["./run-server.sh", "--evn=.env.prod"];