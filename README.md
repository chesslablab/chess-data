## Chess Data

[![Build Status](https://app.travis-ci.com/chesslablab/chess-data.svg?branch=master)](https://app.travis-ci.com/github/chesslablab/chess-data)

A chess database, data science and machine learning with [Rubix ML](https://github.com/RubixML/ML).

### Setup

Clone the `chesslablab/chess-data` repo into your projects folder as it is described in the following example:

    $ git clone git@github.com:chesslablab/chess-data.git

Then `cd` the `chess-data` directory and install the Composer dependencies:

    $ composer install

Create an `.env` file:

    $ cp .env.example .env

Update the environment variables in your `.env` file if necessary:

```text
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=chess
DB_USERNAME=root
DB_PASSWORD=
```

If restarting the computer, the `DB_HOST` variable may need to be updated with the new IP of the `chess_data_mysql` container. Here's how to assign the new value to the `IP_ADDRESS` variable on the command line.

```
$ IP_ADDRESS="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' chess_data_mysql)"
```

And this is how to assign the IP to `DB_HOST`.

```
$ sed -i "s/DB_HOST=.*/DB_HOST=${IP_ADDRESS}/g" .env
```

> For further information, read the [`bash/prod/start.sh`](https://github.com/chesslablab/chess-data/blob/master/bash/prod/start.sh) script.

Finally, create the `chess` database:

    $ php cli/db-create.php

### Command Line Interface (CLI)

### License

The GNU General Public License.

### Contributions

See the [contributing guidelines](https://github.com/chesslablab/chess-server/blob/master/CONTRIBUTING.md).

Happy learning and coding! Thank you, and keep it up.
