## Chess Data

[![Build Status](https://travis-ci.org/programarivm/chess-data.svg?branch=master)](https://travis-ci.org/programarivm/chess-data)

<p align="center">
	<img src="https://github.com/programarivm/php-chess/blob/master/resources/chess-board.jpg" />
</p>

This repo provides you with CLI tools to manage a [chess](https://github.com/programarivm/pgn-chess) database of PGN games as well as to train a supervised model with Rubix ML.

For further information on how to visually study the supervised data please visit [Heuristics Quest](https://github.com/programarivm/heuristics-quest).

### Set Up

Create an `.env` file:

    $ cp .env.example .env

Start the Docker containers:

	$ docker-compose up --build

Find out the IP of your MySQL container and update the `DB_HOST` in your `.env` file accordingly:

	$ docker inspect -f '{{range .NetworkSettings.Networks}}{{.Gateway}}{{end}}' chess_data_mysql

### Command Line Interface (CLI)

#### `cli/db/create.php`

Create the `chess` database with the `games` table containing STR tag pairs and movetexts:

    $ php cli/db/create.php
    This will remove the current chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

Create the `chess` database with the `games` table containing STR tag pairs, movetexts and heuristic snapshots too for further supervised training:

    $ php cli/db/create.php --heuristics

> **Note**: A so-called heuristic snapshot is intended to capture a particular feature of a chess game mainly for the purpose of being plotted on a chart for further visual study. For example, heuristic snapshots such as attack, center or material, are helpful to plot charts and get insights on the efficiency of programmer-defined heuristic evaluation functions. For further information please look at the programmer-defined heuristic evaluation functions available at [programarivm/pgn-chess/src/Heuristic/](https://github.com/programarivm/pgn-chess/tree/master/src/Heuristic).

#### `cli/db/seed.php`

Seed the database with STR tag pairs and movetexts:

	$ php cli/db/seed.php data/players/Adams.pgn
	This will search for valid PGN games in the file.
	Large files (for example 50MB) may take a few seconds to be inserted into the database.
	Do you want to proceed? (Y/N): y
	15 games did not pass the validation.
	3234 games out of a total of 3249 are OK.

Seed the database with STR tag pairs, movetexts and heuristic snapshots too for further supervised training:

	$ php cli/db/seed.php data/players/Adams.pgn --heuristics

#### `cli/pgn/validate.php`

Validates that the PGN syntax in a text file is correct:

	$ php cli/pgn/validate.php data/players/Akobian.pgn
	This will search for syntax errors in the PGN file.
	Large files (for example 50MB) may take a few seconds to be parsed. Games not passing the validation will be printed.
	Do you want to proceed? (Y/N): y
	1353 games out of a total of 1353 are OK.

#### `cli/play/beginner.php`

Play with the `beginner.model`:

	$ php cli/play/beginner.php
	Prediction: 570.13386056267
	Decoded: c6

#### `cli/prepare/beginner.php`

Create the `1_100_beginner.csv` dataset with the games identified with an ID from `1` to `100`:

	$ php cli/prepare/beginner.php 1 100

#### `cli/train/beginner.php`

Train the `beginner.model` with the `1_100_beginner.csv` dataset:

	$ php cli/train/beginner.php 1_100_beginner.csv
	[2020-08-02 15:32:14] beginner.INFO: Learner init MLP Regressor {hidden_layers: [0: Dense {neurons: 100, alpha: 0, bias: true, weight_initializer: He, bias_initializer: Constant {value: 0}}, 1: Activation {activation_fn: ReLU}, 2: Dense {neurons: 100, alpha: 0, bias: true, weight_initializer: He, bias_initializer: Constant {value: 0}}, 3: Activation {activation_fn: ReLU}, 4: Dense {neurons: 50, alpha: 0, bias: true, weight_initializer: He, bias_initializer: Constant {value: 0}}, 5: Activation {activation_fn: ReLU}, 6: Dense {neurons: 50, alpha: 0, bias: true, weight_initializer: He, bias_initializer: Constant {value: 0}}, 7: Activation {activation_fn: ReLU}], batch_size: 128, optimizer: RMS Prop {rate: 0.001, decay: 0.1}, alpha: 0.001, epochs: 100, min_change: 1.0E-5, window: 3, hold_out: 0.1, cost_fn: Least Squares, metric: R Squared}
	[2020-08-02 15:32:14] beginner.INFO: Training started
	[2020-08-02 15:32:25] beginner.INFO: Epoch 1 R Squared=0.94634926347514 Least Squares=94238.878402936
	[2020-08-02 15:32:37] beginner.INFO: Epoch 2 R Squared=0.96649146513525 Least Squares=1142.1373874594
	[2020-08-02 15:32:51] beginner.INFO: Epoch 3 R Squared=0.97117243174116 Least Squares=1185.2010264815
	[2020-08-02 15:33:04] beginner.INFO: Epoch 4 R Squared=0.94546383822505 Least Squares=1062.9393909794
	[2020-08-02 15:33:17] beginner.INFO: Epoch 5 R Squared=0.97429089706881 Least Squares=1021.7216523576
	[2020-08-02 15:33:29] beginner.INFO: Epoch 6 R Squared=0.96820369201207 Least Squares=1013.5754980441
	[2020-08-02 15:33:41] beginner.INFO: Epoch 7 R Squared=0.9778175516864 Least Squares=899.41283826994
	[2020-08-02 15:33:52] beginner.INFO: Epoch 8 R Squared=0.96860370301558 Least Squares=918.29175554535
	[2020-08-02 15:34:02] beginner.INFO: Epoch 9 R Squared=0.97610453676813 Least Squares=929.87241344354
	[2020-08-02 15:34:14] beginner.INFO: Epoch 10 R Squared=0.97839266028844 Least Squares=906.37782887962
	[2020-08-02 15:34:25] beginner.INFO: Epoch 11 R Squared=0.95885220810568 Least Squares=871.08193382194
	[2020-08-02 15:34:37] beginner.INFO: Epoch 12 R Squared=0.95919889786785 Least Squares=865.28135340945
	[2020-08-02 15:34:47] beginner.INFO: Epoch 13 R Squared=0.97794930923409 Least Squares=880.09646901067
	[2020-08-02 15:34:47] beginner.INFO: Parameters restored from snapshot at epoch 10.
	[2020-08-02 15:34:47] beginner.INFO: Training complete

### Bash Scripts

#### `bash/load.sh`

Load STR tag pairs and movetexts from all PGN files stored in the data folder:

	$ bash/load.sh
	This will load all PGN files stored in the data folder. Are you sure to continue? (y|n) y

	1002 games did not pass the validation.
	104023 games out of a total of 105025 are OK.
	Loading games for 593 s...
	The loading of games is completed.

Load STR tag pairs, movetexts and heuristic snapshots too:

	$ bash/load.sh --heuristics

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
