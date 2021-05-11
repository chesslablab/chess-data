## Chess Data

[![Build Status](https://travis-ci.org/programarivm/chess-data.svg?branch=master)](https://travis-ci.org/programarivm/chess-data)

CLI tools to manage a [PHP Chess](https://github.com/programarivm/pgn-chess) database of PGN games as well as to prepare data and train a supervised learning model with [Rubix ML](https://github.com/RubixML/ML).

### Live Demo

The supervised learning process is all about using suitable heuristics such as king safety, attack, material or connectivity, among others. But how can we measure the efficiency of a given chess heuristic? This is where plotting data on nice charts comes to the rescue!

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
| movetext | varchar(3072)      | YES  |     | NULL    |                |
+----------+--------------------+------+-----+---------+----------------+
11 rows in set (0.01 sec)

mysql>
```

##### Example:

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

A so-called heuristic picture consists of a group of heuristic snapshots such as attack, center or material, among others. It is intended to capture the current state of a chess game at any given time, and can be plotted on a chart for further visual study. [Heuristic pictures](https://github.com/programarivm/php-chess/tree/master/src/Heuristic/Picture) are mainly used for supervised training.

#### Seed the `games` Table

```text
$ php cli/db-seed.php -h
USAGE:
   db-seed.php <OPTIONS> <filepath>

   Seeds the chess database with the specified PGN games.                                                                                                                                       


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

	$ php cli/db-seed.php data/players/Carlsen.pgn

With the PGN games (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in `data/players/Carlsen.pgn`:

	$ php cli/db-seed.php --heuristics data/players/Carlsen.pgn

With all PGN files (STR tag pairs and movetexts) found in the given folder:

	$ php cli/db-seed.php data/players

With all PGN files (STR tag pairs, movetexts and heuristic pictures too for further data visualization) found in the given folder:

	$ php cli/db-seed.php --heuristics data/players

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
$ php cli/data-prepare/visualization/heuristics.php -h
USAGE:
   heuristics.php <OPTIONS> <n> <player>

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

	$ php cli/data-prepare/visualization/heuristics.php --win 25 "Capablanca Jose Raul"

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

#### Data Preparation for Further AI Training

```text
$ php cli/data-prepare/training/heuristics.php -h
USAGE:
   heuristics.php <OPTIONS> <n> <player>

   Creates a prepared CSV dataset of heuristics in the dataset/training folder.                                                                                                                 


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

Creates the `dataset/training/capablanca_jose_raul_win.csv` file:

	$ php cli/data-prepare/training/heuristics.php --win 25 "Capablanca Jose Raul"

#### MLP Regressor Training

```text
$ php cli/model-train.php -h
USAGE:
   model-train.php <OPTIONS> <name> <dataset>

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

##### Example:

Train the `a1.model` with the `capablanca_jose_raul_win.csv` dataset previously created:

```text
$ php cli/model-train.php a1 capablanca_jose_raul_win.csv
[2021-05-11 10:06:23] /usr/share/chess-data/cli/../model/a1.model.INFO: MLP Regressor (hidden layers: [0: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: ReLU), 2: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 3: Activation (activation fn: ReLU), 4: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 5: Activation (activation fn: ReLU), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: Activation (activation fn: ReLU)], batch size: 128, optimizer: RMS Prop (rate: 0.001, decay: 0.1), alpha: 0.001, epochs: 100, min change: 1.0E-5, window: 3, hold out: 0.1, cost fn: Least Squares, metric: R Squared) initialized
[2021-05-11 10:06:25] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 1 - R Squared: -21.977075242487, Least Squares: 3048.0327192976
[2021-05-11 10:06:27] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 2 - R Squared: -16.119157901837, Least Squares: 2677.4103144119
[2021-05-11 10:06:28] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 3 - R Squared: -5.5604829505942, Least Squares: 1611.2312651338
[2021-05-11 10:06:30] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 4 - R Squared: 0.40066067524523, Least Squares: 409.41878766798
[2021-05-11 10:06:32] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 5 - R Squared: 0.76720906407256, Least Squares: 38.288661774423
[2021-05-11 10:06:34] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 6 - R Squared: 0.77918417305545, Least Squares: 24.497300525509
[2021-05-11 10:06:35] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 7 - R Squared: 0.79450482628664, Least Squares: 23.065597920467
[2021-05-11 10:06:37] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 8 - R Squared: 0.81658117481614, Least Squares: 21.06679045279
[2021-05-11 10:06:39] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 9 - R Squared: 0.82433009302746, Least Squares: 18.652937147032
[2021-05-11 10:06:40] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 10 - R Squared: 0.86071592769812, Least Squares: 20.763986335691
[2021-05-11 10:06:42] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 11 - R Squared: 0.8614145286486, Least Squares: 17.474112631318
[2021-05-11 10:06:44] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 12 - R Squared: 0.87268987066944, Least Squares: 16.194675045903
[2021-05-11 10:06:46] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 13 - R Squared: 0.88274658820633, Least Squares: 13.848522329275
[2021-05-11 10:06:47] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 14 - R Squared: 0.87703058693158, Least Squares: 10.737224306881
[2021-05-11 10:06:49] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 15 - R Squared: 0.92573315528165, Least Squares: 12.846237860686
[2021-05-11 10:06:51] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 16 - R Squared: 0.90040774456324, Least Squares: 7.8936047979492
[2021-05-11 10:06:53] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 17 - R Squared: 0.94172413423487, Least Squares: 9.4060766448951
[2021-05-11 10:06:54] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 18 - R Squared: 0.95672478313976, Least Squares: 5.0895210838141
[2021-05-11 10:06:56] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 19 - R Squared: 0.96057051126074, Least Squares: 11.277841548068
[2021-05-11 10:06:58] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 20 - R Squared: 0.95876244220792, Least Squares: 4.1673627104464
[2021-05-11 10:06:59] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 21 - R Squared: 0.94029693117993, Least Squares: 8.9490641617517
[2021-05-11 10:07:01] /usr/share/chess-data/cli/../model/a1.model.INFO: Epoch 22 - R Squared: 0.95416716971312, Least Squares: 4.6506877546537
[2021-05-11 10:07:01] /usr/share/chess-data/cli/../model/a1.model.INFO: Network restored from snapshot at epoch 19
[2021-05-11 10:07:01] /usr/share/chess-data/cli/../model/a1.model.INFO: Training complete
```

This will create the `model/a1.model` file, which then can be trained in batches again with more prepared data.

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

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
