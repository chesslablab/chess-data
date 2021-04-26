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

#### Seed the `games` Table

With the PGN games (STR tag pairs and movetexts) found in `data/players/Carlsen.pgn`:

	$ php cli/db-seed.php data/players/Carlsen.pgn

With the PGN games (STR tag pairs, movetexts and heuristic pictures too for further supervised training) found in `data/players/Carlsen.pgn`:

	$ php cli/db-seed.php --heuristics data/players/Carlsen.pgn

With all PGN files (STR tag pairs and movetexts) found in the given folder:

	$ php cli/db-seed.php data/players

With all PGN files (STR tag pairs, movetexts and heuristic pictures too for further supervised training) found in the given folder:

	$ php cli/db-seed.php --heuristics data/players

#### PGN Syntax Checker

This is how to check that a text file contains valid PGN syntax:

	$ php cli/pgn-validate.php data/players/Carlsen.pgn

#### Data Preparation for Further Visualization

Create the `dataset/visualization/capablanca_jose_raul_win.json` file of heuristics with 25 random games where Jose Raul Capablanca wins:

	$ php cli/data-prepare/visualization/heuristics.php --win 25 "Capablanca Jose Raul"

Create the `dataset/visualization/capablanca_jose_raul_lose.json` file of heuristics with 25 random games where Jose Raul Capablanca loses:

	$ php cli/data-prepare/visualization/heuristics.php --lose 25 "Capablanca Jose Raul"

Create the `dataset/visualization/capablanca_jose_raul_draw.json` file of heuristics with 25 random draw games of Jose Raul Capablanca:

	$ php cli/data-prepare/visualization/heuristics.php --draw 25 "Capablanca Jose Raul"

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

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
