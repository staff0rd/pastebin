# pastebin
Send your text to pastebin at the commandline

## develop

```bash
cd src

# restore composer packages
docker run --rm -it -cv $PWD:/app composer install # linux
docker run --rm -it -v %cd%:/app composer install # windows

# run
docker run --rm -it -v $PWD:/app -w /app php:cli php pastebin.php # linux
docker run --rm -it -v %cd%:/app -w /app php:cli php pastebin.php # windows
```

## build

To build the stand-alone docker image:

``bash
docker build -t staff0rd/pastebin .
```