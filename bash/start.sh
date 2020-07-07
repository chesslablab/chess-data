#!/bin/bash

read -p "This will bootstrap the server. Are you sure to continue? (y|n) " -n 1 -r
echo    # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    exit 1
fi

# cd the app's root directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
APP_PATH="$(dirname $DIR)"
cd $APP_PATH

# build the docker containers
cd $APP_PATH
docker-compose up -d

# update the .env file with the containers' ips
GATEWAY="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' pgn_chess_data_mysql)"
sed -i "s/DB_HOST=.*/DB_HOST=${GATEWAY}/g" .env

# install dependencies
docker exec -it pgn_chess_data_php_fpm composer install
docker exec -it pgn_chess_data_php_fpm npm install
