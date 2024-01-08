## Command Line Interface (CLI)

### Seed the Tables with Data

Listed below are some examples of commands to seed the chess tables with data.

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

`games` with your own set of files in `data/games`:

```
$ php cli/seed/games.php data/games
```

`games` file by file in the `data/games` folder, for example:

```
$ php cli/seed/games.php data/games/foobar.pgn
```

Games won't be loaded into the database if containing PGN tags other than the ones supported by the tables created in the [cli/db-create.php](https://github.com/chesslablab/chess-data/blob/master/cli/db-create.php) script. If that is the case you may want to remove the unsupported tags as in the example below.

```
$ find . -name '*.pgn' -print0 | xargs -0 sed -i "/\[PlyCount .*\]/d"
```

### Prepare the Data

Should you want to prepare the data for further AI training, make sure the `endgames` and the `games` tables have been previously seeded with data.

#### Regression From a FEN Position

Prepare the data by playing chess games from a particular FEN position as shown in the example below.

    $ php cli/prepare/training/regression/fen.php 10

This command will play `10` random chess games fetched from the `endgames` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

#### Regression From the Start Position

Prepare the data by playing chess games from the start position as shown in the example below.

    $ php cli/prepare/training/regression/start.php 10

This command will play `10` random chess games fetched from the `games` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [games](https://github.com/chesslablab/chess-data/tree/master/data/games) data.

### AI Training

Create the `ml/regression/checkmate_king_and_rook_vs_king.rbx` file using a prepared dataset.

```text
$ php cli/ml/train/regression.php checkmate_king_and_rook_vs_king fen_100_1646828057.csv
```

### Create JSON Files for the Frontend

The following commands will create a bunch of JSON files in the `output` folder which are intended to be used by [React Chess](https://github.com/chesslablab/react-chess).

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
