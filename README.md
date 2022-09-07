## Chess Data

[![Build Status](https://app.travis-ci.com/chesslablab/chess-data.svg?branch=master)](https://app.travis-ci.com/github/chesslablab/chess-data)

A chess database, data science and machine learning with [Rubix ML](https://github.com/RubixML/ML).

### Setup

Clone the `chesslablab/chess-data` repo into your projects folder as it is described in the following example:

    $ git clone git@github.com:chesslablab/chess-data.git

Then `cd` the `chess-data` directory and install the Composer dependencies:

    $ composer install

Create an `.env` file:

    $ cp .env.example .env

Update the environment variables in your `.env` file if necessary:

```text
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=chess
DB_USERNAME=root
DB_PASSWORD=
```

If restarting the computer, the `DB_HOST` variable may need to be updated with the new IP of the `chess_data_mysql` container. Here's how to assign the new value to the `IP_ADDRESS` variable on the command line.

```
$ IP_ADDRESS="$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' chess_data_mysql)"
```

And this is how to assign the IP to `DB_HOST`.

```
$ sed -i "s/DB_HOST=.*/DB_HOST=${IP_ADDRESS}/g" .env
```

For further information, read the [`bash/prod/start.sh`](https://github.com/chesslablab/chess-data/blob/master/bash/prod/start.sh) script.

### Command Line Interface (CLI)

Listed below are some examples of command-line tools to work with the chess database.

Create the `chess` database:

    $ php cli/db-create.php

Seed the tables:

`endgames` with the files found in `data/endgames`:

	$ php cli/seed/endgames.php data/endgames

`openings` with the files found in `data/openings`:

	$ php cli/seed/openings.php data/openings

`players` with the files found in `data/players`:

	$ php cli/seed/players.php data/players

`players` file by file, for example:

	$ php cli/seed/players.php data/players/Carlsen.pgn

#### Data Preparation for Further AI Training

First things first, make sure the `endgames` and the `players` tables have been seeded with data.

##### Classification From a FEN Position

Prepares the data by playing chess games from a particular FEN position as shown in the example below.

    $ php cli/prepare/training/classification/fen.php 100

This command will play `100` random chess games fetched from the `endgames` table to create a CSV file:

```text
$ more dataset/training/classification/fen_100_1649233046.csv
-1;-1;-1;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;0
-1;-1;-1;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
-1;-1;-1;-0.83;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
-1;-0.91;0;-0.87;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;10
-1;-1;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;0
-1;-0.88;0;-1;0;-0.67;0;0;0;0;0;0;0;0;0;0;0;0;0;3
-1;-0.84;0;-0.87;0;-0.33;0;0;0;0;0;0;0;0;0;0;0;0;0;3
-1;-0.56;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;3
-1;-0.4;0;-0.88;-1;-0.5;0;0;0;0;0;0;0;0;0;0;0;0;0;6
-1;0;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;3
...
```

It's particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

##### Classification From the Start Position

Prepares the data by playing chess games from the start position as shown in the example below.

    $ php cli/prepare/training/classification/start.php 100

This command will play `100` random chess games fetched from the `players` table to create a CSV file:

```text
$ more dataset/training/classification/start_100_1649233461.csv
0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
-1;-0.05;-0.67;0.29;0.5;0;1;-1;0;0;0;1;0;0;0;0;0;0;0;21
0;0.19;-0.56;0;0.5;0;0;0;0;0;0;0;0;0;0;0;0;0;0;1
0;0.26;-0.64;0.33;-0.33;0;0;0;0;0;0;0;-1;0;0;0;0;0;0;68
0;0.33;-0.25;0.36;-0.34;1;0;0;0;0;1;0;-1;0;0;0;0;0;0;70
0;0.06;-0.08;0;-0.67;0;0;0;0;0;1;0;-1;0;0;0;-1;0;0;132
0;0.06;0;0;-0.67;0;0;0;0;0;1;0;0;0;0;0;-1;0;0;124
0;0.35;0.25;0;-0.67;0;0;0;0;0;1;0;0;0;0;0;-1;0;0;124
...
```

It's particularly helpful to prepare [players](https://github.com/chesslablab/chess-data/tree/master/data/players) data.

##### Regression From a FEN Position

Prepares the data by playing chess games from a particular FEN position as shown in the example below.

    $ php cli/prepare/training/regression/fen.php 100

This command will play `100` random chess games fetched from the `endgames` table to create a CSV file:

```text
$ more dataset/training/regression/fen_100_1649233063.csv
-1;1;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-2
-1;-0.5;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-3.5
-1;-0.33;0;-0.79;0;-0.67;0;0;0;0;0;0;0;0;0;0;0;0;0;-2.79
-1;-0.67;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-3.67
-1;-1;-1;-0.79;0;-0.6;0;0;0;0;0;0;0;0;0;0;0;0;0;-4.39
-1;-0.13;0;-1;0;-0.6;0;0;0;0;0;0;0;0;0;0;0;0;0;-2.73
-1;-0.42;0;-1;0;-0.6;0;0;0;0;0;0;0;0;0;0;0;0;0;-3.02
-1;-0.95;0;-1;0;-0.67;0;0;0;0;0;0;0;0;0;0;0;0;0;-3.62
-1;-1;0;-0.88;-1;-0.5;0;0;0;0;0;0;0;0;0;0;0;0;0;-4.38
-1;1;0;-1;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-2
...
```

It's particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

##### Regression From the Start Position

Prepares the data by playing chess games from the start position as shown in the example below.

    $ php cli/prepare/training/regression/start.php 100

This command will play `100` random chess games fetched from the `players` table to create a CSV file:

```text
$ more dataset/training/regression/start_100_1649233251.csv
0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0
1;0.97;-0.6;1;-0.5;1;-1;-1;0;0;0;1;0;0;0;0;0;0;0;1.87
0;-0.53;1;-0.6;-1;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-2.13
0;-0.53;0.57;-0.5;-0.5;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-1.96
0;-0.52;0.43;-0.6;-0.5;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-2.19
0;-0.52;0.37;-0.6;-0.5;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-2.25
0;-0.48;0.13;-0.64;-0.33;-1;0;0;0;0;0;1;0;0;0;0;0;0;0;-1.32
0;-0.48;0.13;-0.64;0;-1;0;0;0;0;0;1;0;0;0;0;0;0;0;-0.99
0;-0.52;0.37;-0.5;0;-1;0;0;0;0;0;0;0;0;0;0;0;0;0;-1.65
...
```

It's particularly helpful to prepare [players](https://github.com/chesslablab/chess-data/tree/master/data/players) data.

#### AI Training

Creates the `model/classification/checkmate_king_and_rook_vs_king.model` with a dataset previously created:

```text
$ php cli/model/train/classification.php checkmate_king_and_rook_vs_king fen_100_1646827021.csv
[2022-03-09 12:05:52] /usr/share/chess-data/cli/model/train/../../../model/classification/checkmate_king_and_rook_vs_king.model.INFO: Multilayer Perceptron (hidden layers: [0: Dense (neurons: 200, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: Leaky ReLU (leakage: 0.1)), 2: Dropout (ratio: 0.3), 3: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 4: Activation (activation fn: Leaky ReLU (leakage: 0.1)), 5: Dropout (ratio: 0.3), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: PReLU (initializer: Constant (value: 0.25))], batch size: 128, optimizer: Adam (rate: 0.001, momentum decay: 0.1, norm decay: 0.001), alpha: 0.0001, epochs: 1000, min change: 0.001, window: 3, hold out: 0.1, cost fn: Cross Entropy, metric: MCC) initialized
[2022-03-09 12:05:53] /usr/share/chess-data/cli/model/train/../../../model/classification/checkmate_king_and_rook_vs_king.model.INFO: Epoch 1 - MCC: 0, Cross Entropy: 0.30452727522162
...
[2022-03-09 12:06:01] /usr/share/chess-data/cli/model/train/../../../model/classification/checkmate_king_and_rook_vs_king.model.INFO: Epoch 10 - MCC: 0.6986111440265, Cross Entropy: 0.048305028494862
[2022-03-09 12:06:01] /usr/share/chess-data/cli/model/train/../../../model/classification/checkmate_king_and_rook_vs_king.model.INFO: Training complete
```

Creates the `model/regression/checkmate_king_and_rook_vs_king.model` with a dataset previously created:

```text
$ php cli/model/train/regression.php checkmate_king_and_rook_vs_king fen_100_1646828057.csv
[2022-03-09 12:16:10] /usr/share/chess-data/cli/model/train/../../../model/regression/checkmate_king_and_rook_vs_king.model.INFO: MLP Regressor (hidden layers: [0: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 1: Activation (activation fn: ReLU), 2: Dense (neurons: 100, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 3: Activation (activation fn: ReLU), 4: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 5: Activation (activation fn: ReLU), 6: Dense (neurons: 50, alpha: 0, bias: true, weight initializer: He, bias initializer: Constant (value: 0)), 7: Activation (activation fn: ReLU)], batch size: 128, optimizer: RMS Prop (rate: 0.001, decay: 0.1), alpha: 0.001, epochs: 100, min change: 1.0E-5, window: 3, hold out: 0.1, cost fn: Least Squares, metric: R Squared) initialized
[2022-03-09 12:16:11] /usr/share/chess-data/cli/model/train/../../../model/regression/checkmate_king_and_rook_vs_king.model.INFO: Epoch 1 - R Squared: -14.078856221542, Least Squares: 12.669363724318
...
[2022-03-09 12:16:20] /usr/share/chess-data/cli/model/train/../../../model/regression/checkmate_king_and_rook_vs_king.model.INFO: Epoch 17 - R Squared: 0.75379244160716, Least Squares: 0.081250471387402
[2022-03-09 12:16:20] /usr/share/chess-data/cli/model/train/../../../model/regression/checkmate_king_and_rook_vs_king.model.INFO: Network restored from snapshot at epoch 14
[2022-03-09 12:16:20] /usr/share/chess-data/cli/model/train/../../../model/regression/checkmate_king_and_rook_vs_king.model.INFO: Training complete
```

### License

The GNU General Public License.

### Contributions

See the [contributing guidelines](https://github.com/chesslablab/chess-server/blob/master/CONTRIBUTING.md).

Happy learning and coding! Thank you, and keep it up.
