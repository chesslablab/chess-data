# Prepare the Data

Should you want to prepare the data for further AI training, make sure the `endgames` and the `games` tables have been previously seeded with data.

## Regression From a FEN Position

Prepare the data by playing chess games from a particular FEN position as shown in the example below.

```text
php cli/prepare/training/regression/fen.php 10
```

This command will play `10` random chess games fetched from the `endgames` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [endgames](https://github.com/chesslablab/chess-data/tree/master/data/endgames) data.

## Regression From the Start Position

Prepare the data by playing chess games from the start position as shown in the example below.

```text
php cli/prepare/training/regression/start.php 10
```

This command will play `10` random chess games fetched from the `games` table to create a prepared CSV dataset in the `dataset/training/regression` folder. It is particularly helpful to prepare [games](https://github.com/chesslablab/chess-data/tree/master/data/games) data.
