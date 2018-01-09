FROM takatost/nginx-php-fpm:latest

MAINTAINER JohnWang <wangjiajun@vchangyi.com>

COPY build/deploy/default.conf /etc/nginx/sites-available/default.conf
COPY / /data/www
COPY build/deploy/queue.conf /etc/supervisor/conf.d/
COPY build/deploy/chown.conf /etc/supervisor/conf.d/

VOLUME /data/www/storage/framework

RUN cd /data/www && \
    composer install --no-dev -vvv && \
    chown -R nginx:nginx /data/www