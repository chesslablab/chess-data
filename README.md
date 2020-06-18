## PGN Chess Data

[![Build Status](https://travis-ci.org/programarivm/pgn-chess-data.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess-data)

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-board.jpg" />
</p>

PGN Chess Data provides you with CLI tools to manage a database of PGN games.

### Set Up

Create an `.env` file:

    cp .env.example .env

Start the server:

    bash/start.sh

Find out your Docker container's IP address:

    docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' pgn_chess_data_php_fpm

### Command Line Interface (CLI)

#### `dbcreate.php`

    php cli/dbcreate.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

#### `dbseed.php`

    php cli/dbseed.php data/games/01.pgn
    This will search for valid PGN games in the file.
    Large files (for example 50MB) may take a few seconds to be inserted into the database.
    Do you want to proceed? (Y/N): y
    Good! This is a valid PGN file. 3201 games were inserted into the database.

#### `tomysql.php`

Converts a PGN file into a MySQL `INSERT` statement.

    php cli/tomysql.php data/games/01.pgn > data/games/01.mysql

#### `syntax.php`

    php cli/syntax.php data/games/01.pgn
	This will search for syntax errors in the PGN file.
	Large files (for example 50MB) may take a few seconds to be parsed.
	Do you want to proceed? (Y/N): y
	Good! This is a valid PGN file.

#### `load.sh`

	bash/load.sh
	This will load all PGN files stored in the data/games folder. Are you sure to continue? (y|n) y
	Good! This is a valid PGN file. 512 games were inserted into the database.
	Loading games for 3 s...
	Good! This is a valid PGN file. 1335 games were inserted into the database.
	Loading games for 11 s...
	The loading of games is completed.


### Development

Should you want to play around with the development environment follow the steps below.

Run the tests:

	docker exec -it pgn_chess_data_php_fpm vendor/bin/phpunit --configuration phpunit-docker.xml

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
