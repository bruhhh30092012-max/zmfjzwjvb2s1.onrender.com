FROM php:8.2-fpm-alpine
RUN apk update && \
    apk add --no-cache nginx gettext
RUN rm -f /etc/nginx/conf.d/*.conf /etc/nginx/nginx.conf
COPY nginx.conf /etc/nginx/nginx.conf 
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
CMD sh -c "envsubst '\$PORT' < /etc/nginx/nginx.conf > /tmp/nginx-ready.conf && php-fpm -D && nginx -c /tmp/nginx-ready.conf -g 'daemon off;'"
COPY fastcgi_params /etc/nginx/fastcgi_params