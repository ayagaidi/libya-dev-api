FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --no-dev --optimize

FROM php:8.4-apache
RUN a2enmod rewrite headers
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY docker/start.sh /usr/local/bin/libya-dev-api-start
RUN chmod +x /usr/local/bin/libya-dev-api-start \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache
EXPOSE 8080
ENTRYPOINT ["libya-dev-api-start"]
