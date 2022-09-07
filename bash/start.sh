#!/bin/bash

read -p "This will bootstrap the production environment. Are you sure to continue? (y|n) " -n 1 -r
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
docker-compose up -d

# write the IP of the chess_data_mysql container in the .env file
IP_ADDRESS="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' chess_data_mysql)"
sed -i "s/DB_HOST=.*/DB_HOST=${IP_ADDRESS}/g" .env
