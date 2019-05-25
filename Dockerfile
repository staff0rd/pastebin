FROM composer:1.8
COPY ./src /app
WORKDIR /app
RUN composer install
CMD [ "php", "./pastebin.php" ]