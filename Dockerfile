FROM webdevops/php-nginx-dev:7.3

ENV WEB_DOCUMENT_ROOT=/var/www/html/
ENV XDEBUG_REMOTE_AUTOSTART=1
ENV XDEBUG_REMOTE_CONNECT_BACK=1
ENV XDEBUG_REMOTE_PORT=9000

WORKDIR /var/www/html

COPY . .

RUN apt-get update
RUN apt install default-mysql-client -y
RUN chown -R www-data:www-data .
RUN usermod -a -G www-data application
RUN chmod 777 -R .
RUN set global log_bin_trust_function_creators=1;

# mysql pawsapp -umateus -p"1234" < ./_utilidades/banco_script.sql -h db
