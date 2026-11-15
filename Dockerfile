FROM php:8.2-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY . /var/www/html/

RUN mkdir -p /var/www/html/sudo/assets/img/books /var/www/html/sudo/assets/magazines \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["sh", "-c", "port=\"${PORT:-80}\"; sed -ri \"s/^[[:space:]]*Listen 80$/Listen ${port}/\" /etc/apache2/ports.conf; sed -ri \"s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/\" /etc/apache2/sites-available/000-default.conf; exec apache2-foreground"]
