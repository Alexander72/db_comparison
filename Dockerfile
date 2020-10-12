##################################
# Cassandra driver build
##################################
FROM debian as cassandra_php_extension_builder

# Build essentials
RUN apt-get update && \
    apt-get install -y unzip apt-transport-https lsb-release curl && \
    curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    apt-get update && \
    apt-get install -y build-essential cmake git libpcre3-dev libgmp-dev libssl-dev libuv1 libuv1-dev php7.4-dev

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
FROM debian

RUN apt-get update && \
    apt-get install -y unzip apt-transport-https lsb-release curl zip unzip zlib1g-dev libzip-dev && \
    curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list && \
    echo "deb https://deb.debian.org/debian stretch-backports main" |  tee /etc/apt/sources.list.d/backports.list && \
    apt-get update

RUN apt-get install -y \
    php7.4 php7.4-dev php7.4-fpm php7.4-zip php7.4-iconv php7.4-ctype php7.4-json php7.4-sockets php7.4-mysql php-pear

# MongoDb
RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> /etc/php/7.4/mods-available/mongodb.ini
RUN phpenmod mongodb

# Cassandra
COPY --from=cassandra_php_extension_builder /usr/lib/php/20190902/cassandra.so /usr/lib/php/20190902/cassandra.so
COPY --from=cassandra_php_extension_builder /usr/local/lib/x86_64-linux-gnu/libcassandra.so.2 /usr/lib/libcassandra.so.2
COPY --from=cassandra_php_extension_builder /lib/x86_64-linux-gnu/libm.so.6 /usr/lib/libm.so.6
COPY --from=cassandra_php_extension_builder /usr/lib/x86_64-linux-gnu/libuv.so.1 /usr/lib/x86_64-linux-gnu/libuv.so.1
COPY --from=cassandra_php_extension_builder /usr/lib/x86_64-linux-gnu/libgmp.so.10 /usr/lib/x86_64-linux-gnu/libgmp.so.10
COPY --from=cassandra_php_extension_builder /lib/x86_64-linux-gnu/libc.so.6 /lib/x86_64-linux-gnu/libc.so.6
RUN echo "extension=cassandra.so" >> /etc/php/7.4/mods-available/cassandra.ini
RUN phpenmod cassandra

# XDebug
RUN pecl install xdebug-2.9.5
COPY docker/app/php/xdebug.ini /etc/php/7.4/mods-available/xdebug.ini
RUN phpenmod xdebug

COPY . /var/www/html/

WORKDIR /var/www/html/

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer

EXPOSE 9000

RUN mkdir /run/php

CMD ["/usr/sbin/php-fpm7.4", "-F"]