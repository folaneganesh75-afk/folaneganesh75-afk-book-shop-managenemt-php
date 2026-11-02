FROM php:8.3-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY app/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD curl -fsS http://localhost/health.php || exit 1