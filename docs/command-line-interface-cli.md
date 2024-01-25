# Command Line Interface (CLI)

## Seed the Tables with Data

Tables are loaded using the files contained in the `data` folder and can be loaded all at once or file by file. Listed below are some examples of commands to seed the `games` table with data.

Seed the `games` table with the examples contained in the `data/example` folder:

```text
$ php cli/seed/games.php data/example
✗ 2 games did not pass the validation.
✓ 4142 games out of a total of 4144 are OK.
✓ 597 games out of a total of 597 are OK.
✗ 1 games did not pass the validation.
✓ 1824 games out of a total of 1825 are OK.
✓ 3878 games out of a total of 3878 are OK.
✓ 4646 games out of a total of 4646 are OK.
✗ 2 games did not pass the validation.
✓ 2126 games out of a total of 2128 are OK.
✓ 2275 games out of a total of 2275 are OK.
✓ 1218 games out of a total of 1218 are OK.
✓ 827 games out of a total of 827 are OK.
✗ 5 games did not pass the validation.
✓ 1341 games out of a total of 1346 are OK.
✓ 5662 games out of a total of 5662 are OK.
✓ 3444 games out of a total of 3444 are OK.
```

Seed the `games` table file by file:

```text
$ php cli/seed/games.php data/example/Anand.pgn
✗ 2 games did not pass the validation.
✓ 4142 games out of a total of 4144 are OK.
```

Seed the `games` table with your own set of files in the `data/games` folder:

```text
$ php cli/seed/games.php data/games
```

Please note that all files in the `data` folder are gitignored except those contained in `data/example`. Also, the chess games won't be loaded into the database if containing PGN tags other than the ones supported by the tables created in the [cli/db-create.php](https://github.com/chesslablab/chess-data/blob/master/cli/db-create.php) script. If that is the case you may want to remove the unsupported tags as in the example below.

```text
$ find . -name '*.pgn' -print0 | xargs -0 sed -i "/\[PlyCount .*\]/d"
```

Listed below are some examples of commands to seed the `endgames` table with data.

```text
$ php cli/seed/endgames.php data/endgames
```

Listed below are some examples of commands to seed the `openings` table with data.

```text
$ php cli/seed/openings.php data/openings
```

## Prepare the Data

Should you want to prepare the data for further AI training, make sure the `endgames` and the `games` tables have been previously seeded with data.

### Regression From a FEN Position

Prepare the data by playing chess games from a particular FEN position as shown in the example below.

```text
$ php cli/prepare/training/regression/fen.php 10
```

This command will play `10` random chess games fetched from the `endgames` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

### Regression From the Start Position

Prepare the data by playing chess games from the start position as shown in the example below.

```text
$ php cli/prepare/training/regression/start.php 10
```

This command will play `10` random chess games fetched from the `games` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [games](https://github.com/chesslablab/chess-data/tree/master/data/games) data.

## AI Training

Create the `ml/regression/checkmate_king_and_rook_vs_king.rbx` file using a prepared dataset.

```text
$ php cli/ml/train/regression.php checkmate_king_and_rook_vs_king fen_100_1646828057.csv
```

## Create JSON Files for the Frontend

The following commands will create a bunch of JSON files in the `output` folder which are intended to be used by [React Chess](https://github.com/chesslablab/react-chess).

Create the `output/autocomplete-events.json` file:

```text
$ php cli/json/autocomplete/events.php
```

Create the `output/autocomplete-players.json` file:

```text
$ php cli/json/autocomplete/players.php
```

Create the `output/draw-rate.json` file:

```
$ php cli/json/stats/draw-rate.php
```

Create the `output/win-rate-for-black.json` file:

```
$ php cli/json/stats/win-rate-for-black.php
```

Create the `output/win-rate-for-white.json` file:

```
$ php cli/json/stats/win-rate-for-white.php
```
