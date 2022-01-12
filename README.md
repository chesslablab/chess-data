## Chess Data

[![Build Status](https://app.travis-ci.com/chesslablab/chess-data.svg?branch=master)](https://app.travis-ci.com/github/chesslablab/chess-data)

CLI tools to manage a [PHP Chess](https://github.com/chesslablab/php-chess) database of PGN games as well as to prepare the data and train a supervised learning model with [Rubix ML](https://github.com/RubixML/ML).

### Setup

Clone the `chesslablab/chess-data` repo into your projects folder as it is described in the following example:

    $ git clone git@github.com:chesslablab/chess-data.git

Then `cd` the `chess-data` directory and install the Composer dependencies:

    $ composer install

Create an `.env` file:

    $ cp .env.example .env

If necessary, update the environment variables in your `.env` file:

```text
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=chess
DB_USERNAME=root
DB_PASSWORD=
```

### Command Line Interface (CLI)

#### Create the Chess Database

```text
$ php cli/db-create.php -h
USAGE:
   db-create.php <OPTIONS>

   Creates the chess database with the games table.                                                                                                                                             


OPTIONS:
   --heuristics                                             Add heuristics for further data visualization.                                                                                      

   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.
```

##### Example:

Creates the `chess` database:

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
| FEN      | char(8)            | YES  |     | NULL    |                |
| movetext | varchar(3072)      | YES  |     | NULL    |                |
+----------+--------------------+------+-----+---------+----------------+
12 rows in set (0,00 sec)

mysql>
```

##### Example:

Alternatively, optional heuristics information can be added too for further data visualization with [Heuristics Quest](https://github.com/programarivm/heuristics-quest):

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
| FEN                  | char(8)            | YES  |     | NULL    |                |
| movetext             | varchar(3072)      | YES  |     | NULL    |                |
| heuristic_picture    | json               | YES  |     | NULL    |                |
| heuristic_evaluation | json               | YES  |     | NULL    |                |
+----------------------+--------------------+------+-----+---------+----------------+
14 rows in set (0,00 sec)

mysql>
```

A so-called [heuristic picture](https://medium.com/geekculture/how-to-take-normalized-heuristic-pictures-79ca0df4cdec) consists of a group of heuristic snapshots such as attack, center or material, among others. It is intended to capture the current state of a chess game at any given time, and can be plotted on a chart for further visual study.

#### Seed the `games` Table

```text
$ php cli/seed/games.php -h
USAGE:
   games.php <OPTIONS> <filepath>

   Seeds the games table with the specified PGN games.                                                                                                                                          


OPTIONS:
   --heuristics                                             Add heuristics for further data visualization.                                                                                      

   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <filepath>                                               PGN file, or folder containing the PGN files.
```

##### Examples:

Seed the database with the PGN games (STR tag pairs and movetexts) found in `data/players/Carlsen.pgn`:

	$ php cli/seed/games.php data/players/Carlsen.pgn

With the PGN games (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in `data/players/Carlsen.pgn`:

	$ php cli/seed/games.php --heuristics data/players/Carlsen.pgn

With all PGN files (STR tag pairs and movetexts) found in the given folder:

	$ php cli/seed/games.php data/players

With all PGN files (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in the given folder:

	$ php cli/seed/games.php --heuristics data/players

#### PGN Syntax Checker

```text
$ php cli/pgn-validate.php -h
USAGE:
   pgn-validate.php <OPTIONS> <filepath>

   PGN syntax validator.                                                                                                                                                                        


OPTIONS:
   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <filepath>                                               PGN file to be validated.
```

##### Example:

Check the PGN syntax in the `data/players/Carlsen.pgn` file:

```text
$ php cli/pgn-validate.php data/players/Carlsen.pgn
Event: 5th YM
Site: Lausanne SUI
Date: 2004.09.20
Round: 3.4
White: Lahno,Kateri
Black: Carlsen,M
Result: 1/2-1/2
WhiteElo: 2472
BlackElo: 2567

Event: 5th YM
Site: Lausanne SUI
Date: 2004.09.20
Round: 3.6
White: Lahno,Kateri
Black: Carlsen,M
Result: 0-1
WhiteElo: 2472
BlackElo: 2567

Event: 5th YM
Site: Lausanne SUI
Date: 2004.09.20
Round: 3.5
White: Carlsen,M
Black: Lahno,Kateri
Result: 1-0
WhiteElo: 2567
BlackElo: 2472

Event: 5th YM
Site: Lausanne SUI
Date: 2004.09.20
Round: 3.3
White: Carlsen,M
Black: Lahno,Kateri
Result: 1/2-1/2
WhiteElo: 2567
BlackElo: 2472

✗ 4 games did not pass the validation.
✓ 3426 games out of a total of 3430 are OK.
```

#### Data Preparation for Further Visualization

```text
$ php cli/data-prepare/visualization/player.php -h
USAGE:
   player.php <OPTIONS> <n> <player>

   Creates a prepared JSON dataset of heuristics in the dataset/visualization folder.                                                                                                           


OPTIONS:
   --win                                                    The player wins.                                                                                                                    

   --lose                                                   The player loses.                                                                                                                   

   --draw                                                   Draw.                                                                                                                               

   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <n>                                                      A random number of games to be queried.                                                                                             
   <player>                                                 The chess player's full name.
```

##### Example:

Creates the `dataset/visualization/capablanca_jose_raul_win.json` file:

	$ php cli/data-prepare/visualization/player.php --win 25 "Capablanca Jose Raul"

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

#### Data Preparation for Further AI Training

##### Classification

Prepares data by playing and studying games played from the start position rather than from a FEN position.

```text
$ php cli/data-prepare/training/classification/start.php -h
USAGE:
   start.php <OPTIONS> <n>

   Creates a prepared CSV dataset in the dataset/training/classification folder.                                                                                         


OPTIONS:
   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <n>                                                      A random number of games to be queried.
```

Example:

Creates the `dataset/training/classification/start_100_1635947115.csv` file:

	$ php cli/data-prepare/training/classification/start.php 100


#### MLP Training

##### Classification

```text
$ php cli/model/train-classification.php -h
USAGE:
   train-classification.php <OPTIONS> <name> <dataset>

   Trains an AI model.                                                                                                                                                                          


OPTIONS:
   -h, --help                                               Display this help screen and exit immediately.                                                                                      

   --no-colors                                              Do not use any colors in output. Useful when piping output to other tools or files.                                                 

   --loglevel <level>                                       Minimum level of messages to display. Default is info. Valid levels are: debug, info, notice, success, warning, error, critical,    
                                                            alert, emergency.                                                                                                                   


ARGUMENTS:
   <name>                                                   The AI model name.                                                                                                                  
   <dataset>                                                A prepared dataset in CSV format.
```

Example:

Train the `a1.model` with the `start_100_1635947115.csv` dataset previously created:

```text
$ php cli/model/train-classification.php a1 start_100_1635947115.csv
[2021-11-03 14:54:45] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Multilayer Perceptron (hidden layers: [0: Dense (neurons: 200, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: Leaky ReLU (leakage: 0.1)), 2: Dropout (ratio: 0.3), 3: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 4: Activation (activation fn: Leaky ReLU (leakage: 0.1)), 5: Dropout (ratio: 0.3), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: PReLU (alpha initializer: Constant (value: 0.25))], batch size: 128, optimizer: Adam (rate: 0.001, momentum decay: 0.1, norm decay: 0.001), alpha: 0.0001, epochs: 1000, min change: 0.001, window: 3, hold out: 0.1, cost fn: Cross Entropy, metric: MCC) initialized
[2021-11-03 14:55:10] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Epoch 1 - MCC: 0.021316814851544, Cross Entropy: 0.011637643059115
[2021-11-03 14:55:35] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Epoch 2 - MCC: 0.1441569667138, Cross Entropy: 0.0090628767718673
[2021-11-03 14:56:00] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Epoch 3 - MCC: 0.18620599479873, Cross Entropy: 0.0071159420224029
[2021-11-03 14:56:25] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Epoch 4 - MCC: 0.24875347460879, Cross Entropy: 0.0060323621254431
[2021-11-03 14:56:52] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Epoch 5 - MCC: 0.2939912191158, Cross Entropy: 0.0053859162470983
[2021-11-03 14:56:52] /home/standard/projects/chess-data/cli/model/../../model/a1.model.INFO: Training complete
```

This will create the `model/a1.model` file, which can be trained in batches again with more prepared data.

#### Play with the AI

Play with the AI.

```text
$ php cli/model-play.php a1.model
chess > d4
chess > 1.d4 d5
chess > Nc3
chess > 1.d4 d5 2.Nc3 Nc6
chess > Nxd5
chess > 1.d4 d5 2.Nc3 Nc6 3.Nxd5 Qd5
chess > quit
```

### License

The GNU General Public License.

### Contributions

See the [contributing guidelines](https://github.com/chesslablab/chess-server/blob/master/CONTRIBUTING.md).

Happy learning and coding! Thank you, and keep it up.
