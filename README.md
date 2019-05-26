# pastebin
Send your text to pastebin at the commandline with docker

## usage

Use the image directly from docker, here's some examples:

```bash

docker run -it staff0rd/pastebin -k <devKey> "paste this text to pastebin!"

docker run -it staff0rd/pastebin --help # get help

cat myfile.log | docker run -i staff0rd/pastebin -k <devKey> -j <userKey> -n "Contents of myfile.log" # push the contents of myfile.log at pastebin under your user

cat myfile.log | docker run -i staff0rd/pastebin -k <devKey> # push the contents of myfile.log at pastebin as a guest post

docker run -it staff0rd/pastebin -k <devKey> -u <userName> --password <password> # get a userkey to use above

```

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

```bash
docker build -t staff0rd/pastebin .
```