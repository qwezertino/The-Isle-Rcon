FROM php:8.2-fpm

RUN apt-get update

RUN apt-get install vim -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install procps -y && \
    apt-get install -y inotify-tools && \
    apt-get install git -y && \
    apt-get install libpq-dev -y && \
    apt-get install libc-ares2 -y && \
    apt-get install -y libc-ares-dev && \
    apt-get install -y libcurl4-openssl-dev && \
    docker-php-ext-install sockets

# Clone the OpenSwoole repository and install
RUN cd /tmp && \
    git clone https://github.com/openswoole/ext-openswoole.git && \
    cd ext-openswoole && \
    git checkout v22.1.2 && \
    phpize && \
    ./configure --enable-openssl \
                --enable-mysqlnd \
                --enable-sockets \
                --enable-http2 \
                --enable-hook-curl \
                --with-postgres \
                --enable-cares && \
    make && make install

# Add OpenSwoole extension to php.ini
RUN touch /usr/local/etc/php/conf.d/openswoole.ini && \
    echo 'extension=openswoole.so' > /usr/local/etc/php/conf.d/openswoole.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
#/app/composer.lock

# RUN /usr/bin/composer install

EXPOSE 8105
# CMD ["/usr/local/bin/php", "/app/index.php"]
# CMD ["/app/start.sh"]
