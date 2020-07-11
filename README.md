## PGN Chess Data

[![Build Status](https://travis-ci.org/programarivm/pgn-chess-data.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess-data)

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-board.jpg" />
</p>

This repo provides you with CLI tools to manage a [PGN Chess](https://github.com/programarivm/pgn-chess) database and an API for the purpose to study chess games as well as to analyze the programmer-defined heuristic evaluation functions available at [programarivm/pgn-chess/src/Heuristic/](https://github.com/programarivm/pgn-chess/tree/master/src/Heuristic).

For further information on how to plot the API data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

### Set Up

Create an `.env` file:

    cp .env.example .env

Start the environment:

    bash/start.sh

### Command Line Interface (CLI)

If you're using Docker find the IP address of the PHP container:

	docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' pgn_chess_data_php_fpm

#### `create.php`

    php cli/create.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

With heuristics evaluation data:

    php cli/create.php --heuristics

#### `seed.php`

	php cli/seed.php data/games/02.pgn
	This will search for valid PGN games in the file.
	Large files (for example 50MB) may take a few seconds to be inserted into the database.
	Do you want to proceed? (Y/N): y
	4 games did not pass the validation.
	1331 games out of a total of 1335 are OK.

With heuristics evaluation data:

	php cli/seed.php data/games/02.pgn --heuristics

#### `validate.php`

	php cli/validate.php data/games/02.pgn
	This will search for syntax errors in the PGN file.
	Large files (for example 50MB) may take a few seconds to be parsed. Games not passing the validation will be printed.
	Do you want to proceed? (Y/N): y
	Event: Gibraltar Masters 2019
	Site: Caleta ENG
	Date: 2019.01.29
	Round: 8.17
	White: Ramirez,Alej
	Black: Cheparinov,I
	Result: 1/2-1/2
	WhiteElo: 2567
	BlackElo: 2691

	Event: Gibraltar Masters 2019
	Site: Caleta ENG
	Date: 2019.01.30
	Round: 9.34
	White: Pigott,J
	Black: Short,N
	Result: 0-1
	WhiteElo: 2387
	BlackElo: 2648

	Event: Gibraltar Masters 2019
	Site: Caleta ENG
	Date: 2019.01.31
	Round: 10.30
	White: Cramling,P
	Black: Yuffa,D
	Result: 0-1
	WhiteElo: 2462
	BlackElo: 2578

	Event: Gibraltar Masters 2019
	Site: Caleta ENG
	Date: 2019.01.31
	Round: 10.26
	White: Harsha,B
	Black: Vocaturo,D
	Result: 1/2-1/2
	WhiteElo: 2481
	BlackElo: 2626

	4 games did not pass the validation.
	1331 games out of a total of 1335 are OK.

#### `load.sh`

	bash/load.sh
	This will load all PGN files stored in the data folder. Are you sure to continue? (y|n) y

	1002 games did not pass the validation.
	104023 games out of a total of 105025 are OK.
	Loading games for 593 s...
	The loading of games is completed.

With heuristics evaluation data:

	bash/load.sh --heuristics

### API

If you're using Docker find the IP address of the NGINX container:

    docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' pgn_chess_data_nginx

To do: Write documentation.

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
