FROM dunglas/frankenphp

ENV TZ=Asia/Seoul

WORKDIR /app

COPY . .

RUN apt-get update && \
  apt-get install -y cron && \
  apt-get clean && \
  mv docker-cron-job /etc/cron.d/ && \
  chmod 0644 /etc/cron.d/docker-cron-job

RUN install-php-extensions \
  pdo_mysql \
  gd \
  intl \
  zip \
  opcache \
  redis \
  memcached

RUN php-zts composer.phar install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

ENTRYPOINT ["/bin/sh", "-c", "cron && exec ./run-server.sh --env=.env.prod"];