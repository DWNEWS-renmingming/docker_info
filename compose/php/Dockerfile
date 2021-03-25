FROM php:7.2-fpm

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zip unzip git \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
        bcmath \
        zip \
        gettext \
        pcntl \
        shmop \
        intl \
        sockets \
        pdo_mysql \
        mysqli \
        opcache 

RUN  pecl install redis-5.3.2 && docker-php-ext-enable redis \
        && pecl install swoole-4.5.2 && docker-php-ext-enable swoole 

# composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer      

#RUN echo "" >> /usr/local/etc/php/php.ini
RUN usermod -u 1000 www-data
