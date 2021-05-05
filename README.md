## Chess Data

[![Build Status](https://travis-ci.org/programarivm/chess-data.svg?branch=master)](https://travis-ci.org/programarivm/chess-data)

<p align="center">
	<img src="https://github.com/programarivm/php-chess/blob/master/resources/chess-board.jpg" />
</p>

CLI tools to manage a [PHP Chess](https://github.com/programarivm/pgn-chess) database of PGN games as well as to prepare data and train a supervised learning model with [Rubix ML](https://github.com/RubixML/ML).

### Live Demo

The supervised learning process is all about using suitable heuristics such as king safety, attack, material or connectivity, among others. But how can we measure the efficiency of a given chess heuristic? This is where plotting data on nice charts comes to the rescue!

A live demo is available at [https://programarivm.github.io/heuristics-quest/](https://programarivm.github.io/heuristics-quest/).

For further information please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

### Set Up

Create an `.env` file:

    $ cp .env.example .env

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

Alternatively, an optional heuristic picture can be added too for further data visualization with [Heuristics Quest](https://github.com/programarivm/heuristics-quest):

    $ php cli/db-create.php --heuristics

In which case the `games` table will look as it is described next:

```text
mysql> describe games;
+----------------------+--------------------+------+-----+---------+----------------+
| Field                | Type               | Null | Key | Default | Extra          |
+----------------------+--------------------+------+-----+---------+----------------+
| id                   | mediumint unsigned | NO   | PRI | NULL    | auto_increment |
| Event                | char(64)           | YES  |     | NULL    |                |
| Site                 | char(64)           | YES  |     | NULL    |                |
| Date                 | char(16)           | YES  |     | NULL    |                |
| White                | char(32)           | YES  |     | NULL    |                |
| Black                | char(32)           | YES  |     | NULL    |                |
| Result               | char(8)            | YES  |     | NULL    |                |
| WhiteElo             | char(8)            | YES  |     | NULL    |                |
| BlackElo             | char(8)            | YES  |     | NULL    |                |
| ECO                  | char(8)            | YES  |     | NULL    |                |
| movetext             | varchar(3072)      | YES  |     | NULL    |                |
| heuristic_picture    | json               | YES  |     | NULL    |                |
| heuristic_evaluation | json               | YES  |     | NULL    |                |
+----------------------+--------------------+------+-----+---------+----------------+
13 rows in set (0.01 sec)

mysql>
```

A so-called heuristic picture consists of a group of heuristic snapshots such as attack, center or material, among others. It is intended to capture the current state of a chess game at any given time, and can be plotted on a chart for further visual study. Heuristic pictures are mainly used for supervised training.

For further information, please look at the programmer-defined heuristic evaluation functions available at [programarivm/pgn-chess/src/Heuristic/](https://github.com/programarivm/pgn-chess/tree/master/src/Heuristic).

#### Seed the `games` Table

With the PGN games (STR tag pairs and movetexts) found in `data/players/Carlsen.pgn`:

	$ php cli/db-seed.php data/players/Carlsen.pgn

With the PGN games (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in `data/players/Carlsen.pgn`:

	$ php cli/db-seed.php --heuristics data/players/Carlsen.pgn

With all PGN files (STR tag pairs and movetexts) found in the given folder:

	$ php cli/db-seed.php data/players

With all PGN files (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in the given folder:

	$ php cli/db-seed.php --heuristics data/players

#### PGN Syntax Checker

This is how to check that a text file contains valid PGN syntax:

	$ php cli/pgn-validate.php data/players/Carlsen.pgn

#### Data Preparation for Further Visualization

```text
$ php cli/data-prepare/visualization/heuristics.php -h
USAGE:
   heuristics.php <OPTIONS> <n> <player>

   Creates a prepared dataset of heuristics in JSON format for further visualization. The file is created in the dataset/visualization folder.                                                  


OPTIONS:
   --win                                                    White wins.                                                                                                                         

   --lose                                                   White loses.                                                                                                                        

   --draw                                                   Draw.                                                                                                                               

   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <n>                                                      A random number of games to be queried.                                                                                             
   <player>                                                 The chess player's full name.
```

##### Examples:

Creates the `dataset/visualization/capablanca_jose_raul_win.json` file:

	$ php cli/data-prepare/visualization/heuristics.php --win 25 "Capablanca Jose Raul"

Creates the `dataset/visualization/capablanca_jose_raul_lose.json` file:

	$ php cli/data-prepare/visualization/heuristics.php --lose 25 "Capablanca Jose Raul"

Creates the `dataset/visualization/capablanca_jose_raul_draw.json` file:

	$ php cli/data-prepare/visualization/heuristics.php --draw 25 "Capablanca Jose Raul"

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

#### Data Preparation for Further AI Training

```text
$ php cli/data-prepare/training/heuristics.php -h
USAGE:
   heuristics.php <OPTIONS> <name> <from> <to>

   Creates a prepared dataset of heuristics in CSV format for further training.                                                                                                                 


OPTIONS:
   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <name>                                                   The model name to be trained.                                                                                                       
   <from>                                                   The id range.                                                                                                                       
   <to>                                                     The id range.
```

Assuming we want to train a model named `a1`, this is how to create a `dataset/training/a1_1_100.csv` file of heuristics with ID games ranging from `1` to `100`:

	$ php cli/data-prepare/training/heuristics.php a1 1 100

The resulting file may look like this:

```text
1;1;0.23;0.83;0.17;0;3027
1;1;0.23;0.71;0.3;0;3006
1;1;0.23;0.71;0.35;0.33;3087
1;1;0.23;0.79;0.43;0.67;3219
1;1;0.23;0.83;0.61;0.33;3225
1;1;0.23;0.75;0.65;0.33;3197
0.89;1;0.46;0.83;0.52;0.33;3216
0.89;1;0.46;0.71;0.61;0.67;3251
0.89;0.5;0.46;0.88;0.61;0.67;2786
...
```

#### MLP Regressor Training

Create and train the `a1.model` with the `a1_1_100.csv` dataset previously created:

	$ php cli/model-train.php a1 a1_1_100.csv
	[2021-04-21 15:41:00] /usr/share/chess-data/cli/../model/beginner.model.INFO: MLP Regressor (hidden layers: [0: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: ReLU), 2: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 3: Activation (activation fn: ReLU), 4: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 5: Activation (activation fn: ReLU), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: Activation (activation fn: ReLU)], batch size: 128, optimizer: RMS Prop (rate: 0.001, decay: 0.1), alpha: 0.001, epochs: 100, min change: 1.0E-5, window: 3, hold out: 0.1, cost fn: Least Squares, metric: R Squared) initialized
	[2021-04-21 15:41:07] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 1 - R Squared: -23.73636944188, Least Squares: 12830049411.344
	[2021-04-21 15:41:14] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 2 - R Squared: -23.441372496336, Least Squares: 12758274787.884
	[2021-04-21 15:41:21] /usr/share/chess-data/cli/../model/beginner.model.INFO: Epoch 3 - R Squared: -22.537729961097, Least Squares: 12468383042.14
	...
	[2021-04-21 15:48:13] /usr/share/chess-data/cli/../model/beginner.model.INFO: Network restored from snapshot at epoch 57
	[2021-04-21 15:48:13] /usr/share/chess-data/cli/../model/beginner.model.INFO: Training complete

This will create the `model/a1.model` file which then can be trained in batches again with more prepared data.

#### Play with the AI

Play with the AI.

```text
$ php cli/model-play.php a2.model
chess > d4
chess > 1.d4 d5
chess > Nc3
chess > 1.d4 d5 2.Nc3 Nc6
chess > Nxd5
chess > 1.d4 d5 2.Nc3 Nc6 3.Nxd5 Qd5
chess > quit
```

### Models Available

Name | Description | Evaluation | Heuristic |
---- | ----------- | ---------- | --------- |
`model/a1.model` | 3,418 games by Magnus Carlsen | <ul><li>Material</li><li>King safety</li><li>Center</li><li>Connectivity</li><li>Space</li><li>Attack</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`
`model/a2.model` | 5,239 games by Carlsen and Polgar | <ul><li>Material</li><li>King safety</li><li>Center</li><li>Connectivity</li><li>Space</li><li>Attack</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`
`model/a3.model` | 10,000 games by chess grandmasters | <ul><li>Material</li><li>King safety</li><li>Center</li><li>Connectivity</li><li>Space</li><li>Attack</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`
`model/a4.model` | 3,500 games by chess grandmasters | <ul><li>Material</li><li>King safety</li><li>Attacked</li><li>Center</li><li>Connectivity</li><li>Space</li><li>Attack</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`
`model/a5.model` | 3,500 games by chess grandmasters | <ul><li>Material</li><li>Space</li><li>Center</li><li>King safety</li><li>Connectivity</li><li>Attack</li><li>Pressure</li><li>Pressured</li><li>Attacked</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`
`model/a6.model` | 3,500 games by chess grandmasters | <ul><li>Material</li><li>King safety</li><li>Attacked</li><li>Center</li><li>Connectivity</li><li>Space</li><li>Attack</li></ul> | `Chess\Heuristic\LinearCombinationEvaluation`

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
