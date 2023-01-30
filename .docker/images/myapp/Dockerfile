FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT /app

RUN apt update && apt install -y \
    unzip

RUN a2enmod rewrite

# install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

# install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install -y symfony-cli

WORKDIR /app

EXPOSE 80
EXPOSE 443
