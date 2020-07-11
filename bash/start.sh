#!/bin/bash

read -p "This will bootstrap the development environment. Are you sure to continue? (y|n) " -n 1 -r
echo    # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    exit 1
fi

# cd the app's root directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
APP_PATH="$(dirname $DIR)"
cd $APP_PATH

# generate a development SSL certificate
cd docker/nginx/ssl
openssl genrsa -des3 -passout pass:foobar -out pgn-chess-data.local.pem 2048
openssl req -passin pass:foobar -new -sha256 -key pgn-chess-data.local.pem -subj "/C=US/ST=CA/O=pgn-chess-data, Inc./CN=pgn-chess-data.local" -reqexts SAN -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=DNS:pgn-chess-data.local,DNS:www.pgn-chess-data.local")) -out pgn-chess-data.local.csr
openssl x509 -passin pass:foobar -req -days 365 -in pgn-chess-data.local.csr -signkey pgn-chess-data.local.pem -out pgn-chess-data.local.crt
openssl rsa -passin pass:foobar -in pgn-chess-data.local.pem -out pgn-chess-data.local.key

# build the docker containers
cd $APP_PATH
docker-compose up -d

# update the .env file with the containers' ips
GATEWAY="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' pgn_chess_data_mysql)"
sed -i "s/DB_HOST=.*/DB_HOST=${GATEWAY}/g" .env

# install dependencies
docker exec -it pgn_chess_data_php_fpm composer install
