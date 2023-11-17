## Command Line Interface (CLI)

### Seed the Tables with Data

Listed below are some examples of commands to seed the chess tables with data.

`compositions` with your own set of files in `data/compositions`:

```
$ php cli/seed/compositions.php data/compositions
```

`compositions` file by file in the `data/compositions` folder, for example:

```
$ php cli/seed/compositions.php data/compositions/foobar.pgn
```

`endgames` with your own set of files in `data/endgames`:

```
$ php cli/seed/endgames.php data/endgames
```

`endgames` file by file in the `data/endgames` folder, for example:

```
$ php cli/seed/endgames.php data/endgames/foobar.pgn
```

`openings` with the files found in `data/openings`:

```
$ php cli/seed/openings.php data/openings
```

`players` with your own set of files in `data/players`:

```
$ php cli/seed/players.php data/players
```

`players` file by file in the `data/players` folder, for example:

```
$ php cli/seed/players.php data/players/foobar.pgn
```

Games won't be loaded into the database if containing PGN tags other than the ones supported by the tables created in the [cli/db-create.php](https://github.com/chesslablab/chess-data/blob/master/cli/db-create.php) script. If that is the case you may want to remove the unsupported tags as in the example below.

```
$ find . -name '*.pgn' -print0 | xargs -0 sed -i "/\[PlyCount .*\]/d"
```

### Prepare the Data

Should you want to prepare the data for further AI training, make sure the `endgames` and the `players` tables have been previously seeded with data.

#### Classification From a FEN Position

Prepare the data by playing chess games from a particular FEN position as shown in the example below.

    $ php cli/prepare/training/classification/fen.php 10

This command will play `10` random chess games fetched from the `endgames` table to create a prepared CSV dataset in the `dataset/training/classification` folder. It is particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

#### Classification From the Start Position

Prepare the data by playing chess games from the start position as shown in the example below.

    $ php cli/prepare/training/classification/start.php 10

This command will play `10` random chess games fetched from the `players` table to create a prepared CSV dataset in the `dataset/training/classification` folder. It is particularly helpful to prepare [players](https://github.com/chesslablab/chess-data/tree/master/data/players) data.

#### Regression From a FEN Position

Prepare the data by playing chess games from a particular FEN position as shown in the example below.

    $ php cli/prepare/training/regression/fen.php 10

This command will play `10` random chess games fetched from the `endgames` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

#### Regression From the Start Position

Prepare the data by playing chess games from the start position as shown in the example below.

    $ php cli/prepare/training/regression/start.php 10

This command will play `10` random chess games fetched from the `players` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [players](https://github.com/chesslablab/chess-data/tree/master/data/players) data.

### AI Training

Create the `ml/classification/checkmate_king_and_rook_vs_king.rbx` file using a prepared dataset.

```text
$ php cli/ml/train/classification.php checkmate_king_and_rook_vs_king fen_100_1646827021.csv
```

Create the `ml/regression/checkmate_king_and_rook_vs_king.rbx` file using a prepared dataset.

```text
$ php cli/ml/train/regression.php checkmate_king_and_rook_vs_king fen_100_1646828057.csv
```

### Create JSON Files for the Frontend

The following commands will create a bunch of JSON files in the `output` folder which are intended to be used by [Redux Chess](https://github.com/chesslablab/redux-chess).

Create the `output/autocomplete-events.json` file:

	$ php cli/json/autocomplete/events.php

Create the `output/autocomplete-players.json` file:

	$ php cli/json/autocomplete/players.php

Create the `output/draw-rate.json` file:

	$ php cli/json/stats/draw-rate.php

Create the `output/win-rate-for-black.json` file:

	$ php cli/json/stats/win-rate-for-black.php

Create the `output/win-rate-for-white.json` file:

	$ php cli/json/stats/win-rate-for-white.php
