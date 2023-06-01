FROM lehongduc87/php-nginx
LABEL authors="lehongduc87@gmail.com"

COPY . /www

RUN chmod -Rf 777 /www/storage
RUN chmod +x /www/start.sh

WORKDIR /www

EXPOSE 80 443
