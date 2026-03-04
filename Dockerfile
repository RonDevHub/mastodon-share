FROM php:8.2-apache

# Sicherheit: Apache härten
RUN echo "ServerTokens ProductOnly" >> /etc/apache2/apache2.conf \
    && echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# PHP-Erweiterungen für cURL (für den Instanz-Check)
RUN apt-get update && apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install curl

# Arbeitsverzeichnis
WORKDIR /var/www/html

# Code kopieren
COPY ./src /var/www/html/

# Rechte für www-data
RUN chown -R www-data:www-data /var/www/html