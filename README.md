## PGN Chess

[![Latest Stable Version](https://poser.pugx.org/programarivm/pgn-chess/v/stable)](https://packagist.org/packages/programarivm/pgn-chess)
[![Build Status](https://travis-ci.org/programarivm/pgn-chess.svg?branch=master)](https://travis-ci.org/programarivm/pgn-chess)
[![Total Downloads](https://poser.pugx.org/programarivm/pgn-chess/downloads)](https://packagist.org/packages/programarivm/pgn-chess)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-board.jpg" />
</p>

PGN Chess is a chess board representation to play and validate PGN games. It also provides with a PHP CLI command to seed a database with PGN games. See it in action at [PGN Chess Server](https://github.com/programarivm/pgn-chess-server), which is a WebSocket server listening to chess commands.

### Install

Via composer:

    $ composer require programarivm/pgn-chess

### Instantiation

Just instantiate a game and play PGN moves:

```php
use PGNChess\Game;

$game = new Game;

$isLegalMove = $game->play('w', 'e4');
```
All action takes place in the `$game` object. The call to the `$game->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board.

### Game methods

#### `isCheck()`

Finds out if the game is in check.

```php
$isCheck = $game->isCheck();
```

#### `isMate()`

Finds out if the game is over.

```php
$isMate = $game->isMate();
```

#### `status()`

Gets the current game's status.

    $status = $game->status();

`$status` is a PHP object containing useful information about the game being played.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `turn`         | The current player's turn                  |
| `squares`      | Free/used squares on the board             |
| `control`      | Squares controlled by both players         |
| `castling`     | The castling status of the two kings       |

The following sequence of moves:

```php
$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$status = $game->status();
```

Will generate a `$status` object which properties are accessed this way:

```php
// current turn
$game->status()->turn;

// used/free squares
$game->status()->squares->used;
$game->status()->squares->free;

// white's control
$game->status()->control->space->{'w'};
$game->status()->control->attack->{'w'};

// black's control
$game->status()->control->space->{'b'};
$game->status()->control->attack->{'b'};

// white's castling
$game->status()->castling->{'w'}->castled;
$game->status()->castling->{'w'}->{'O-O'};
$game->status()->castling->{'w'}->{'O-O-O'};

// black's castling
$game->status()->castling->{'b'}->castled;
$game->status()->castling->{'b'}->{'O-O'};
$game->status()->castling->{'b'}->{'O-O-O'};
```

#### `piece()`

Gets a piece by its position on the board.

    $piece = $game->piece('c8');

`$piece` is a PHP object containing information about the chess piece selected:

| Property       | Description                                |
|----------------|--------------------------------------------|
| `color`        | The piece's color in PGN format            |
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `moves`        | The piece's possible moves                 |

The following code:

```php
$game = new Game;

$piece = $game->piece('b8');
```

Will generate a `$piece` object which properties are accessed this way:

```php
$piece->color;
$piece->identity;
$piece->position;
$piece->moves;
```

#### `pieces()`

Gets the pieces on the board by color.

    $blackPieces = $game->pieces('b');

`$blackPieces` is an array of PHP objects containing information about black pieces.

| Property       | Description                                |
|----------------|--------------------------------------------|
| `identity`     | The piece's identity in PGN format         |
| `position`     | The piece's position on the board          |
| `moves`        | The piece's possible moves                 |

The following code:

```php
$game = new Game;

$blackPieces = $game->pieces('b');
```

Will generate a `$blackPieces` array of objects which properties are accessed this way:

```php
$blackPieces[1]->identity;
$blackPieces[1]->position;
$blackPieces[1]->moves;
```

#### `history()`

Gets the game's history.

    $history = $game->history();

The following sequence of moves:

```php
$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$history = $game->history();
```

Will generate a `$history` array of objects.

#### `captures()`

Gets the pieces captured by both players.

    $captures = $game->captures();

The following sequence of moves:

```php
$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'c6');
$game->play('w', 'Bf4');
$game->play('b', 'd5');
$game->play('w', 'Nc3');
$game->play('b', 'Nf6');
$game->play('w', 'Bxb8');
$game->play('b', 'Rxb8');

$captures = $game->captures();
```

Will generate a `$captures` array of objects.

#### `metadata()`

Fetches from the database random metadata of the current game.

    $metadata = $game->metadata();

The following sequence of moves:

```php
$game = new Game;

$game->play('w', 'd4');
$game->play('b', 'd5');
$game->play('w', 'Bf4');

$metadata = $game->metadata();
```

Might return a `$metadata` array as the described below.

    Array
    (
        [Event] => 11. KIIT Elite Open 2018
        [Site] => Bhubaneswar IND
        [Date] => 2018.05.28
        [Round] => 6.5
        [White] => Kravtsiv, Martyn
        [Black] => Das, Sayantan
        [Result] => 1-0
        [WhiteElo] => 2655
        [BlackElo] => 2437
        [EventDate] => 2018.05.25
        [ECO] => D02
        [movetext] => 1.d4 d5 2.Bf4 Nf6 3.Nf3 c5 4.e3 Nc6 5.Nbd2 e6 6.c3 Bd6 7.Bg3 O-O 8.Bd3 b6 9.e4 dxe4 10.Nxe4 Be7 11.Nxf6+ Bxf6 12.dxc5 bxc5 13.Qc2 h6 14.h4 Qe7 15.O-O-O Rd8 16.Bh7+ Kh8 17.Rxd8+ Nxd8 18.Be4 Bb7 19.Rd1 Rc8 20.Qa4 Bxe4 21.Qxe4 Nc6 22.Bd6 Qe8 23.Bxc5 Na5 24.Qb4 Nc6 25.Qa4 e5 26.Qe4 Na5 27.Rd5 Qb5 28.Bb4 Qf1+ 29.Rd1 Qb5 30.Bxa5 Qxa5 31.a3 Qb5 32.g3 Rc4 33.Qd5 Qb3 34.Nxe5 Rxc3+ 35.bxc3 Qxc3+ 36.Kb1 Bxe5 37.Qd8+ Kh7 38.Qd3+ Qxd3+ 39.Rxd3 Kg6 40.Kc2 Bc7 41.Rd5 Bb6 42.f3 h5 43.Kd3 f6 44.Ke2 Bc7 45.g4 hxg4 46.fxg4 Bb6 47.a4 1-0
    )

The random `$metadata` will vary in subsequent calls according to the chess games stored in the particular database.

    Array
    (
        [Event] => 1. Longtou Cup 2018
        [Site] => Qinhuangdao CHN
        [Date] => 2018.05.28
        [Round] => 3.2
        [White] => Antipov, Mikhail Al
        [Black] => Dai, Changren
        [Result] => 1/2-1/2
        [WhiteElo] => 2597
        [BlackElo] => 2436
        [EventDate] => 2018.05.26
        [ECO] => D00
        [movetext] => 1.d4 d5 2.Bf4 Nf6 3.e3 e6 4.Nd2 c5 5.c3 Nc6 6.Ngf3 Bd6 7.Bg3 O-O 8.Bb5 h6 9.Qe2 Bxg3 10.hxg3 Qb6 11.Rb1 Bd7 12.Bd3 Ng4 13.Nh4 f5 14.f3 Nf6 15.Ng6 Rfe8 16.f4 Kf7 17.Ne5+ Nxe5 18.fxe5 Ng4 19.Nf3 Qd8 20.Nh2 Nxh2 21.Rxh2 Qg5 22.Kf2 Kg8 23.Rh5 Qg6 24.dxc5 Rec8 25.Rbh1 Rxc5 26.R1h4 Rf8 27.g4 fxg4+ 28.Kg1 Bb5 29.Bxb5 Rxb5 30.Rxh6 Qb1+ 31.Kh2 1/2-1/2
    )

### Command Line Interface (CLI)

Make sure to have an `.env` file in your app's root folder:

	DB_DRIVER=mysql
	DB_HOST=localhost
	DB_NAME=pgn_chess_test
	DB_PASSWORD=password
	DB_PORT=3306
	DB_USER=root

#### `db-create.php`

Creates a MySQL PGN Chess database.

    php db-create.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

#### `pgn-tomysql.php`

Converts a PGN file into a MySQL `INSERT` statement.

    php pgn-tomysql.php example.pgn > example.sql

This is the output generated.

    INSERT INTO games (Event, Site, Date, Round, White, Black, Result, WhiteTitle, BlackTitle, WhiteElo, BlackElo, WhiteUSCF, BlackUSCF, WhiteNA, BlackNA, WhiteType, BlackType, EventDate, EventSponsor, Section, Stage, Board, Opening, Variation, SubVariation, ECO, NIC, Time, UTCTime, UTCDate, TimeControl, SetUp, FEN, Termination, Annotator, Mode, PlyCount, movetext) VALUES ('TCh-FRA Top 12 2018', 'Brest FRA', '2018.05.28', '3.3', 'Dornbusch, Tatiana', 'Feller, Sebastien', '0-1', null, null, '2290', '2574', null, null, null, null, null, null, '2018.05.26', null, null, null, null, null, null, null, 'E11', null, null, null, null, null, null, null, null, null, null, null, '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5 8.d5 e59.O-O Nbd7 10.b4 a5 11.b5 g6 12.Nb1 Nh5 13.Bh6 Ng7 14.Qc2 Nb6 15.Nbd2 Bd716.Kh1 Qc7 17.Rae1 Rae8 18.Ng1 Bc8 19.f4 exf4 20.Qc3 f6 21.Rxf4 Nd7 22.Bc2Ne5 23.h3 Nf7 24.Bxg7 Kxg7 25.Rf2 Kg8 26.Ngf3 Ne5 27.Ref1 Bd8 28.Kg1 Qg729.Nb3 b6 30.Nbd2 Qh6 31.Nxe5 fxe5 32.Rxf8+ Rxf8 33.Rxf8+ Kxf8 34.a4 Kg735.Bd1 Qh4 36.Kf1 Qf4+ 37.Bf3 h5 38.Ke2 Kh6 39.Qe3 g5 40.Qxf4 exf4 41.e5dxe5 42.d6 Be6 43.Bc6 g4 44.hxg4 hxg4 45.Kd3 Kg5 46.Ke4 Kf6 47.Be8 Bg8 48.Bd7 Be6 49.Bc6 Bf5+ 50.Kd5 f3 51.g3 f2 52.Be8 e4 53.Nf1 Be6+ 54.Kc6 Ke555.Bh5 Kd4 0-1'),('11. KIIT Elite Open 2018', 'Bhubaneswar IND', '2018.05.28', '5.3', 'Nitin, S', 'Amonatov, Farrukh', '0-1', null, null, '2432', '2608', null, null, null, null, null, null, '2018.05.25', null, null, null, null, null, null, null, 'B90', null, null, null, null, null, null, null, null, null, null, null, '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 a6 6.Be3 e5 7.Nb3 Be7 8.h3 b59.a4 b4 10.Nd5 Nbd7 11.Nxe7 Kxe7 12.Qd2 a5 13.O-O-O Qc7 14.f3 Bb7 15.g4Rhc8 16.Bb5 Bc6 17.Bxc6 Qxc6 18.Bg5 Ra6 19.Qe2 Nb6 20.Bxf6+ gxf6 21.f4 Qc422.Rd3 Nxa4 23.g5 fxg5 24.fxe5 dxe5 25.Rhd1 Rac6 26.R1d2 Nc5 27.Nxc5 Qxc528.Qg4 Kf8 29.Rf3 Kg8 30.Qf5 Rf8 31.Rg3 Rg6 32.Rd5 Qc4 33.Rxg5 b3 34.Rd2bxc2 35.Rxc2 Qd4 36.Rcg2 Rd8 37.Rxg6+ hxg6 38.Rxg6+ Kf8 39.Qf3 Qa4 40.Rg2Qa1+ 41.Kc2 Rc8+ 42.Kd3 Qc1 43.Qf6 Qc4+ 44.Ke3 Qd4+ 45.Kf3 Qd3+ 46.Kg4Qxe4+ 47.Kg3 Qg6+ 48.Qxg6 fxg6 49.Kf3 Kf7 50.Ke4 Kf6 51.Rf2+ Ke6 52.Rg2Rc4+ 53.Ke3 Kf5 54.Rf2+ Rf4 55.Rc2 Rh4 56.Rf2+ Ke6 57.Rf3 Rb4 0-1');

#### `pgn-syntax.php`

Checks the syntax of a PGN file.

    php pgnsyntax.php games.pgn
    This will search for syntax errors in the PGN file.
    Large files (for example 50MB) may take a few seconds to be parsed.
    Do you want to proceed? (Y/N): y
    Whoops! Sorry but this is not a valid PGN file.
    --------------------------------------------------------
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.3
    White: Nitin, S
    Black: Amonatov, Farrukh
    Result: 0-1
    WhiteElo: 2432
    BlackElo: 2608
    EventDate: 2018.05.25
    ECO: B90
    --------------------------------------------------------
    Event: 11. KIIT Elite Open 2018
    Site: Bhubaneswar IND
    Date: 2018.05.28
    Round: 5.17
    White: Raahul, V S
    Black: Neverov, Valeriy
    Result: 1/2-1/2
    WhiteElo: 2231
    BlackElo: 2496
    EventDate: 2018.05.25
    ECO: A25
    1.foo f5 2.Nc3 Nf6 3.g3 e5 4.Bg2 Nc6 5.e3 Bb4 6.Nge2 O-O 7.O-O d6 8.Nd5 Nxd5 9.cxd5 Ne7 10.d4 Ba5 11.b4 Bb6 12.dxe5 dxe5 13.Qb3 Kh8 14.a4 a6 15. Bb2 Ng6 16.a5 Ba7 17.Qc3 Re8 18.Nf4 Re7 19.Nxg6+ hxg6 20.Rac1 Rb8 21.b5 b6 22.Ba3 Rf7 23.axb6 Rxb6 24.Bc5 e4 25.Bxb6 Bxb6 26.bxa6 Bxa6 27.Rfd1 Rd7 28.Qe5 Rd6 29.Bf1 Bxf1 30.Kxf1 c6 31.Kg2 Kh7 32.h4 cxd5 33.h5 Qd7 34.Rh1 g5 35.Rc8 f4 36.h6 f3+ 37.Kg1 Rxh6 38.Rh8+ Kg6 39.R1xh6+ gxh6 40.Rg8+ Kh5 41.Qf6 Bxe3 42.fxe3 Qc7 43.Qg6+ Kg4 44.Qe6+ Kxg3 45.Rc8 Qa7 46.Qd6+ Kg4 47.Qe6+ Kg3 48.Qd6+ Kg4 1/2-1/2
    --------------------------------------------------------
    Event: TCh-FRA Top 12 2018
    Site: Brest FRA
    Date: 2018.05.28
    Round: 3.6
    White: Eljanov, Pavel
    Black: Ragger, Markus
    Result: 1/2-1/2
    WhiteElo: 2702
    BlackElo: 2672
    EventDate: 2018.05.26
    ECO: A34
    1.Nf3 Nf6 20.c4 c5 3.Nc3 d5 4.cxd5 Nxd5 5.e3 e6 6.Bb5+ Bd7 7.Be2 Nc6 8.O-O Be7 9.d4 cxd4 10.Nxd5 exd5 11.exd4 O-O 12.Ne5 Bf5 13.Be3 Bf6 14.Nxc6 bxc6 15.Bd3 Bxd3 16.Qxd3 Qb6 17.Rfc1 Rfe8 18.Qc3 1/2-1/2
    --------------------------------------------------------
    Please check these games. Do they provide the STR (Seven Tag Roster)? Is the movetext valid?

#### `db-seed.php`

Seeds the PGN Chess database with games.

    php db-seed.php Alekhine.pgn
    This will search for valid PGN games in the file.
    Large files (for example 50MB) may take a few seconds to be inserted into the database.
    Do you want to proceed? (Y/N): y
    Good! This is a valid PGN file. 3201 games were inserted into the database.

### Development

Should you want to play around with the development environment follow the steps below.

Create an `.env` file:

	cp .env.example .env

Bootstrap the environment:

	bash/dev/start.sh

Create the testing database:

	docker exec -it pgn_chess_php_fpm php cli/db-create.php

Seed the testing database with sample games:

	docker exec -it pgn_chess_php_fpm php cli/db-seed.php data/integration/01_games.pgn
	docker exec -it pgn_chess_php_fpm php cli/db-seed.php data/integration/02_games.pgn

Run the tests:

	docker exec -it pgn_chess_php_fpm vendor/bin/phpunit --configuration phpunit-docker.xml

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
