FROM composer:1.8
COPY ./src /app
WORKDIR /app
RUN composer install
ENTRYPOINT [ "php", "./pastebin.php" ]