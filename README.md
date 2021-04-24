## Chess Data

[![Build Status](https://travis-ci.org/programarivm/chess-data.svg?branch=master)](https://travis-ci.org/programarivm/chess-data)

<p align="center">
	<img src="https://github.com/programarivm/php-chess/blob/master/resources/chess-board.jpg" />
</p>

This repo provides you with CLI tools to manage a [PHP Chess](https://github.com/programarivm/pgn-chess) database of PGN games as well as to prepare data and train a supervised learning model with [Rubix ML](https://github.com/RubixML/ML).

The supervised learning process is all about using [suitable heuristics](https://github.com/programarivm/php-chess/tree/master/src/Heuristic) such as king safety, attack, material or connectivity, among others. But how can we measure the efficiency of a given chess heuristic? This is where plotting data on nice charts comes to the rescue!

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

### Set Up

Create an `.env` file:

    $ cp .env.example .env

Generate an SSL certificate:

	$ $ bash/dev/genssl.sh

Start the Docker containers:

	$ docker-compose up --build

Then find out the IP of your MySQL container:

	$ docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' chess_data_mysql

And update the `DB_HOST` in your `.env` file accordingly:

```text
DB_DRIVER=mysql
DB_HOST=172.18.0.1
DB_PORT=3306
DB_DATABASE=chess
DB_USERNAME=root
DB_PASSWORD=
```

### Command Line Interface (CLI)

#### Create the Chess Database

Create the `chess` database with the `games` table:

    $ php cli/db-create.php

The `games` table will look as described next:

```text
mysql> use chess;
Database changed
mysql> describe games;
+----------+--------------------+------+-----+---------+----------------+
| Field    | Type               | Null | Key | Default | Extra          |
+----------+--------------------+------+-----+---------+----------------+
| id       | mediumint unsigned | NO   | PRI | NULL    | auto_increment |
| Event    | char(64)           | YES  |     | NULL    |                |
| Site     | char(64)           | YES  |     | NULL    |                |
| Date     | char(16)           | YES  |     | NULL    |                |
| White    | char(32)           | YES  |     | NULL    |                |
| Black    | char(32)           | YES  |     | NULL    |                |
| Result   | char(8)            | YES  |     | NULL    |                |
| WhiteElo | char(8)            | YES  |     | NULL    |                |
| BlackElo | char(8)            | YES  |     | NULL    |                |
| ECO      | char(8)            | YES  |     | NULL    |                |
| movetext | varchar(3072)      | YES  |     | NULL    |                |
+----------+--------------------+------+-----+---------+----------------+
11 rows in set (0.01 sec)

mysql>
```

Alternatively, an optional heuristic picture can be added too for further supervised training:

    $ php cli/db-create.php --heuristic_picture

In which case the `games` table will look as it is described next:

```text
mysql> describe games;
+-------------------+--------------------+------+-----+---------+----------------+
| Field             | Type               | Null | Key | Default | Extra          |
+-------------------+--------------------+------+-----+---------+----------------+
| id                | mediumint unsigned | NO   | PRI | NULL    | auto_increment |
| Event             | char(64)           | YES  |     | NULL    |                |
| Site              | char(64)           | YES  |     | NULL    |                |
| Date              | char(16)           | YES  |     | NULL    |                |
| White             | char(32)           | YES  |     | NULL    |                |
| Black             | char(32)           | YES  |     | NULL    |                |
| Result            | char(8)            | YES  |     | NULL    |                |
| WhiteElo          | char(8)            | YES  |     | NULL    |                |
| BlackElo          | char(8)            | YES  |     | NULL    |                |
| ECO               | char(8)            | YES  |     | NULL    |                |
| movetext          | varchar(3072)      | YES  |     | NULL    |                |
| heuristic_picture | json               | YES  |     | NULL    |                |
+-------------------+--------------------+------+-----+---------+----------------+
12 rows in set (0.01 sec)

mysql>
```

A so-called heuristic picture consists of a group of heuristic snapshots such as attack, center or material, among others. It is intended to capture the current state of a chess game at any given time, and can be plotted on a chart for further visual study. Heuristic pictures are mainly used for supervised training. For further information, please look at the programmer-defined heuristic evaluation functions available at [programarivm/pgn-chess/src/Heuristic/](https://github.com/programarivm/pgn-chess/tree/master/src/Heuristic).

#### Seed the Games Table

Seed the `games` table with the PGN games (STR tag pairs and movetexts) found in `data/players/Adams.pgn`:

	$ php cli/db-seed.php data/players/Adams.pgn
	✗ 15 games did not pass the validation.
	✓ 3234 games out of a total of 3249 are OK.

Once the command above is successfully run, this is how the game with `id = 1` will look like:

```text
mysql> SELECT * FROM games WHERE id = 1;
+----+----------------+--------+------------+----------------+-----------------+--------+----------+----------+------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| id | Event          | Site   | Date       | White          | Black           | Result | WhiteElo | BlackElo | ECO  | movetext                                                                                                                                                                                                                                                                                                                                                              |
+----+----------------+--------+------------+----------------+-----------------+--------+----------+----------+------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
|  1 | Lloyds Bank op | London | 1984.??.?? | Adams, Michael | Sedgwick, David | 1-0    |          |          | C05  | 1.e4 e6 2.d4 d5 3.Nd2 Nf6 4.e5 Nfd7 5.f4 c5 6.c3 Nc6 7.Ndf3 cxd4 8.cxd4 f6 9.Bd3 Bb4+ 10.Bd2 Qb6 11.Ne2 fxe5 12.fxe5 O-O 13.a3 Be7 14.Qc2 Rxf3 15.gxf3 Nxd4 16.Nxd4 Qxd4 17.O-O-O Nxe5 18.Bxh7+ Kh8 19.Kb1 Qh4 20.Bc3 Bf6 21.f4 Nc4 22.Bxf6 Qxf6 23.Bd3 b5 24.Qe2 Bd7 25.Rhg1 Be8 26.Rde1 Bf7 27.Rg3 Rc8 28.Reg1 Nd6 29.Rxg7 Nf5 30.R7g5 Rc7 31.Bxf5 exf5 32.Rh5+ 1-0 |
+----+----------------+--------+------------+----------------+-----------------+--------+----------+----------+------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)
```

Seed the `games` table with the PGN games (STR tag pairs, movetexts and heuristic pictures too for further supervised training) found in `data/players/Adams.pgn`:

	$ php cli/db-seed.php --heuristics data/players/Adams.pgn

Once the command above is successfully run, this is how the `heuristic_picture` of the game with `id = 1` looks like:

```text
mysql> SELECT heuristic_picture FROM games WHERE id = 1;
+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| heuristic_picture                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| {"b": [[0.5, 1, 0, 0.97, 0, 0], [0.5, 1, 0, 0.87, 0.06, 0.17], [0.5, 1, 0, 0.94, 0.06, 0.17], [0.5, 1, 0.08, 0.97, 0.12, 0.17], [0.5, 1, 0.08, 1, 0.12, 0.33], [0.5, 1, 0.08, 0.94, 0.12, 0.33], [0.66, 1, 0.15, 0.94, 0.29, 0.33], [0.5, 1, 0.08, 0.9, 0.18, 0.33], [0.5, 1, 0.08, 0.74, 0.29, 0.5], [0.5, 1, 0.08, 0.65, 0.29, 0.5], [0.66, 1, 0.15, 0.61, 0.29, 0.5], [0.5, 0.67, 0.08, 0.65, 0.35, 0.67], [0.5, 0.67, 0.08, 0.61, 0.41, 0.83], [1, 0.67, 0.08, 0.39, 0.71, 0.83], [0.36, 0.67, 0.32, 0.32, 0.41, 1], [0.36, 0.67, 0.75, 0.23, 0.65, 0.67], [0.52, 0.67, 1, 0.19, 0.71, 0.67], [0.36, 0.33, 1, 0.19, 0.76, 0.83], [0.36, 0.33, 0.4, 0.13, 0.82, 0.67], [0.36, 0.33, 0.4, 0.26, 0.65, 0.5], [0.36, 0.33, 0.23, 0.29, 0.53, 1], [0.36, 0.33, 0.23, 0.19, 0.47, 0.67], [0.36, 0.67, 0.23, 0.19, 0.59, 0.5], [0.36, 0.67, 0.23, 0.19, 0.59, 0.5], [0.36, 0.33, 0.23, 0.19, 0.65, 0.5], [0.36, 0.33, 0.23, 0.26, 0.53, 0.5], [0.36, 0.33, 0.23, 0.26, 0.71, 0.5], [0.36, 0.33, 0.23, 0.26, 0.71, 0.33], [0.2, 0, 0.23, 0.1, 0.76, 0.33], [0.2, 0.33, 0.23, 0.13, 0.71, 0.33], [0.22, 0.33, 0.23, 0, 0.71, 0.33], [0.22, 0, 0.23, 0, 0.76, 0.33]], "w": [[0.5, 1, 0.08, 0.87, 0.06, 0], [0.5, 1, 0.15, 0.77, 0.35, 0.17], [0.5, 1, 0.15, 0.84, 0.18, 0.17], [0.5, 1, 0.15, 0.84, 0.24, 0], [0.5, 1, 0.15, 0.87, 0.24, 0.17], [0.5, 1, 0.15, 0.9, 0.35, 0.17], [0.34, 1, 0, 0.9, 0.29, 0.17], [0.5, 1, 0.08, 0.97, 0.29, 0.17], [0.5, 0.67, 0.15, 0.84, 0.47, 0.33], [0.5, 0.67, 0.15, 0.84, 0.47, 0.5], [0.34, 0.67, 0.08, 0.74, 0.41, 0.5], [0.5, 0.67, 0.15, 0.74, 0.65, 0.33], [0.5, 1, 0.15, 0.77, 0.76, 0.17], [0, 0.33, 0.15, 0.45, 0.71, 0.5], [0.64, 0.67, 0.08, 0.35, 0.82, 0.5], [0.64, 0.67, 0.08, 0.26, 0.88, 0.33], [0.48, 0.67, 0, 0.45, 0.59, 0.33], [0.64, 0.33, 0, 0.45, 0.53, 0.17], [0.64, 1, 0, 0.39, 0.65, 0.17], [0.64, 1, 0.08, 0.42, 0.41, 0.33], [0.64, 0.67, 0.15, 0.42, 0.47, 0.33], [0.64, 0.67, 0.15, 0.32, 0.47, 0.33], [0.64, 0.67, 0.08, 0.32, 0.35, 0.17], [0.64, 0.67, 0.08, 0.26, 0.47, 0.33], [0.64, 0.67, 0.08, 0.23, 0.47, 0.5], [0.64, 0.67, 0.08, 0.23, 0.47, 0.5], [0.64, 0.67, 0.08, 0.23, 0.53, 0.5], [0.64, 0, 0.08, 0.23, 0.59, 0.5], [0.8, 0, 0.08, 0.13, 0.59, 0.67], [0.8, 0, 0.08, 0.16, 0.53, 0.5], [0.78, 0, 0.08, 0.06, 0.76, 0.33], [0.78, 0, 0.08, 0.03, 1, 0.5]]} |
+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.01 sec)

mysql>
```

Seed the `games` table with all PGN files (STR tag pairs and movetexts) found in the given folder:

	$ php cli/db-seed.php data/players
	✗ 15 games did not pass the validation.
	✓ 3234 games out of a total of 3249 are OK.
	✓ 1353 games out of a total of 1353 are OK.

Seed the `games` table with all PGN files (STR tag pairs, movetexts and heuristic pictures too for further supervised training) found in the given folder:

	$ php cli/db-seed.php --heuristics data/players
	✗ 20 games did not pass the validation.
	✓ 3229 games out of a total of 3249 are OK.
	✗ 4 games did not pass the validation.
	✓ 1349 games out of a total of 1353 are OK.

#### PGN Syntax Checker

This is how to check that a text file contains valid PGN syntax:

	$ php cli/pgn-validate.php data/players/Akobian.pgn
	✓ 1353 games out of a total of 1353 are OK.

#### Data Preparation for Further Data Visualization

Create the `dataset/visualization/1_100.json` file of heuristics with ID games ranging from `1` to `100`:

	$ php cli/data-prepare/visualization/heuristics.php 1 100

#### Data Preparation for Further AI Training

Create the `dataset/training/1_100.csv` file of heuristics with ID games ranging from `1` to `100`:

	$ php cli/data-prepare/training/heuristics.php 1 100

This is how it may look like:

```text
0.5;1;0.17;0.92;0.32;0;0
0.5;1;0.25;0.83;0.55;0.25;0
0.5;1;0.17;0.86;0.5;0.25;0
0.5;1;0.17;0.92;0.59;0.25;0
0.5;1;0.17;1;0.55;0.25;0
0.5;1;0.25;0.92;0.68;0.5;0
0.38;1;0.17;0.92;0.68;0.5;0
0.38;1;0.17;0.86;0.68;0.5;0
0.38;0.83;0.17;0.69;0.77;0.25;0
0.27;0.83;0.08;0.67;0.68;0.5;0
0.27;0.83;0.17;0.58;0.73;0.75;0
...
```

Create the `dataset/training/1_100.csv` file of events and heuristics with ID games ranging from `1` to `100`:

	$ php cli/data-prepare/training/events.php 1 100

This is how it may look like:

```text
0;0;0;0;0;0;0;0.5;1;0.17;0.92;0.32;0;119955
0;0;0;0;0;0;0;0.5;1;0.25;0.83;0.55;0.25;121079
0;0;0;0;0;0;0;0.5;1;0.17;0.86;0.5;0.25;125532
0;0;0;0;0;0;0;0.5;1;0.17;0.92;0.59;0.25;128057
0;0;0;0;0;1;0;0.5;1;0.17;1;0.55;0.25;126284
0;0;0;1;0;1;0;0.5;1;0.25;0.92;0.68;0.5;126284
0;0;0;1;0;1;0;0.38;1;0.17;0.92;0.68;0.5;139658
0;1;0;1;0;1;0;0.38;1;0.17;0.86;0.68;0.5;144329
0;1;0;1;0;0;0;0.38;0.83;0.17;0.69;0.77;0.25;168550
0;0;0;0;0;0;0;0.27;0.83;0.08;0.67;0.68;0.5;177117
0;0;0;0;0;0;0;0.27;0.83;0.17;0.58;0.73;0.75;142197
...
```

#### MLP Regressor Training

Create the `beginner.model` with the `1_100.csv` dataset:

	$ php cli/model-train.php beginner 1_100.csv
	[2021-04-21 15:41:00] /usr/share/chess-data/cli/../model/beginner.model.INFO: MLP Regressor (hidden layers: [0: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: ReLU), 2: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 3: Activation (activation fn: ReLU), 4: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 5: Activation (activation fn: ReLU), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: Activation (activation fn: ReLU)], batch size: 128, optimizer: RMS Prop (rate: 0.001, decay: 0.1), alpha: 0.001, epochs: 100, min change: 1.0E-5, window: 3, hold out: 0.1, cost fn: Least Squares, metric: R Squared) initialized
	[2021-04-21 15:41:07] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 1 - R Squared: -23.73636944188, Least Squares: 12830049411.344
	[2021-04-21 15:41:14] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 2 - R Squared: -23.441372496336, Least Squares: 12758274787.884
	[2021-04-21 15:41:21] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 3 - R Squared: -22.537729961097, Least Squares: 12468383042.14
	...
	[2021-04-21 15:48:13] /usr/share/chess-data/cli/../model/beginner.model.INFO: Network restored from snapshot at epoch 57
	[2021-04-21 15:48:13] /usr/share/chess-data/cli/../model/beginner.model.INFO: Training complete

The command above will create the `model/beginner.model` file which can be used to keep training the model in batches with more prepared data.

#### Play with the AI

Play with the AI -- for testing purposes for the time being:

	$ php cli/model-play.php
	1.e4 d5 3.e5 Be6

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
