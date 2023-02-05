FROM php:8.1-fpm
RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev 
RUN apt-get install -y libzip-dev
#RUN docker-php-ext-configure --with-gd --with-webp-dir --with-jpeg-dir \
#    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
#    --enable-gd-native-ttf
RUN docker-php-ext-install zip mysqli pdo gd pdo_mysql zlib && docker-php-ext-enable pdo_mysql gd

