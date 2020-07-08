#!/bin/bash

read -p "This will load all PGN files stored in the data folder. Are you sure to continue? (y|n) " -n 1 -r
echo    # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    exit 1
fi

SECONDS=0;

# cd the app's root directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
APP_PATH="$(dirname $DIR)"
cd $APP_PATH

for file in data/players/*
do
  php cli/seed.php $file --quiet $1
  echo "Loading games for $SECONDS s...";
done

echo "The loading of games is completed.";
