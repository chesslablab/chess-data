## PGN Chess

[![Build Status](https://travis-ci.org/programarivm/pgn-chess.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess)
[![Documentation Status](https://readthedocs.org/projects/pgn-chess/badge/?version=latest)](https://pgn-chess.readthedocs.io/en/latest/?badge=latest)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-move.jpg" />
</p>

PGN Chess is a chess board representation to play and validate PGN games (player vs player). It also provides with a PHP CLI command to seed a database with PGN games.

### Install

Via composer:

    $ composer require programarivm/pgn-chess

### Instantiation

Just instantiate a game and play PGN moves:

```php
<?php

use PGNChess\Game;

$game = new Game;

$isLegalMove = $game->play('w', 'e4');
```
All action takes place in the `$game` object. The call to the `$game->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board.

### Documentation

For further information please read the [Documentation](https://pgn-chess.readthedocs.io/en/latest/).

### Development

Should you want to play around with the development environment follow the steps below.

Create an `.env` file:

		cp .env.example .env

Bootstrap the environment:

		bash/dev/start.sh

Create the testing database:

		docker exec -it pgn_chess_php_fpm php cli/db-create.php

Seed the testing database with sample games:

		docker exec -it pgn_chess_php_fpm php cli/db-seed.php tests/integration/data/01-games.pgn

Run the tests:

		docker exec -it pgn_chess_php_fpm vendor/bin/phpunit tests --configuration phpunit-docker.xml

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
