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

RUN mkdir /config

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY index.php /var/www/html
COPY composer.json /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN COMPOSER_CACHE_DIR=/dev/null composer install -d /var/www/html/ --no-dev

RUN chown -R www-data:www-data /var/www/html && chown -R www-data:www-data /config

VOLUME /config

EXPOSE 80

CMD ["/sbin/runit-wrapper"]