FROM phpearth/php:7.2-nginx

ENV \
    # When using Composer, disable the warning about running commands as root/super user
    COMPOSER_ALLOW_SUPERUSER=1 \
    # Persistent runtime dependencies
    DEPS="php7.2-pdo \
          php7.2-pdo_sqlite \
          php7.2-apcu"

RUN set -x \
    && apk add --no-cache $DEPS


COPY docker/ /

COPY index.php /var/www/html
RUN sed -i 's#\$rss.*#\$rss = new dyndns\\server("/data/config.ini", "/data/dyndns.db");#' /var/www/html/index.php
COPY composer.json /var/www/html
COPY src/ /var/www/html/src/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN COMPOSER_CACHE_DIR=/dev/null composer install -d /var/www/html/ --no-dev

RUN chown -R www-data /var/www/html
RUN chown -R www-data /data

VOLUME /data

EXPOSE 80

CMD ["/sbin/runit-wrapper"]