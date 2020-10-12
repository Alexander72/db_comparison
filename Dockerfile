##################################
# Cassandra driver build
##################################
FROM docker.takeaway.com/service:latest as cassandra_php_extension_builder

# Build essentials
RUN apt-get update && \
    apt-get install -y unzip apt-transport-https lsb-release curl && \
    curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    apt-get update && \
    apt-get install -y build-essential cmake git libpcre3-dev libgmp-dev libssl-dev libuv1 libuv1-dev php7.3-dev

# Build driver and extension
RUN git clone https://github.com/datastax/php-driver && \
    cd php-driver && \
    git checkout ea185de889fea5455076b469133fc28843900e23 && \
    git submodule init && \
    git submodule update && \
    cd lib/cpp-driver/ && \
    mkdir build && \
    cd build && \
    cmake .. && \
    make && \
    make install && \
    cd ../../../ext && \
    phpize && \
    cd .. && \
    mkdir build && \
    cd build && \
    ../ext/configure && \
    make && \
    make install

##################################
# Base image
##################################
FROM php:7.3-fpm

RUN apt-get update && \
    apt-get install -y unzip apt-transport-https lsb-release && \
    curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    echo "deb https://deb.debian.org/debian stretch-backports main" |  tee /etc/apt/sources.list.d/backports.list

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev

RUN docker-php-ext-install ctype
RUN docker-php-ext-install iconv
RUN docker-php-ext-install json
RUN docker-php-ext-install sockets
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql

RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install xdebug-2.9.5 && docker-php-ext-enable xdebug

COPY docker/app/php/* /usr/local/etc/php/conf.d

COPY . /var/www/html/

WORKDIR /var/www/html/

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer
