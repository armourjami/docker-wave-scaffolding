FROM ubuntu:16.04

MAINTAINER Jamie Armour <jamiealexanderarmour@gmail.com>

RUN apt-get update && apt-get -y upgrade && apt-get install -y \
    php7.0 \
    php7.0-fpm \
	php7.0-curl \
	php7.0-json \
	php7.0-cgi \
	php7.0-imap \
	php7.0-mysql \
	php7.0-pgsql \
	php7.0-mcrypt \
	php7.0-mbstring \ 
	php7.0-opcache \
	php7.0-bcmath \
	php7.0-readline \
	php7.0-zip \
	php7.0-xml \
	php-pear \
	php-dev \
	nano \
	vim \
    pecl install redis-3.0.0

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

ADD conf/supervisord.conf /etc/supervisord.conf

# Copy our PHP conf
RUN mkdir -p /var/run/php
RUN mkdir -p /var/log/php

RUN rm -Rf /etc/php/7.0/fpm/php-fpm.conf
RUN rm -Rf /etc/php/7.0/fpm/pool.d/*

RUN echo 'extension=amqp.so' > /etc/php/7.0/cli/conf.d/amqp.ini && \
	echo 'extension=event.so' > /etc/php/7.0/cli/conf.d/event.ini && \
	echo 'extension=redis.so' > /etc/php/7.0/cli/conf.d/redis.ini

RUN echo 'extension=amqp.so' > /etc/php/7.0/fpm/conf.d/amqp.ini && \
	echo 'extension=event.so' > /etc/php/7.0/fpm/conf.d/event.ini && \
	echo 'extension=redis.so' > /etc/php/7.0/fpm/conf.d/redis.ini

ADD conf/php/php-fpm.conf /etc/php/7.0/fpm/php-fpm.conf
ADD conf/php/pools/*.conf /etc/php/7.0/fpm/pool.d/

# Copy our nginx config
RUN rm -Rf /etc/nginx/nginx.conf
ADD conf/nginx/nginx.conf /etc/nginx/nginx.conf
ADD conf/nginx/sites-enabled/*.conf /etc/nginx/sites-enabled/

VOLUME ["/var/www"]

EXPOSE 80

ADD docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh
CMD ["/docker-entrypoint.sh"]