FROM php:7.4-fpm

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libpq-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
libpng-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd 
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mysqli
RUN echo "alias ll='ls -la'" >> ~/.bashrc && . ~/.bashrc

RUN pecl install xdebug-2.9.8 && \
    docker-php-ext-enable xdebug

WORKDIR /var/www/html
