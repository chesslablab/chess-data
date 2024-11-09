# Data Mining

The precondition for data mining is to seed the `games` table with data.

The following commands are to populate the columns suffixed with the word `_mine` with pre-calculated data for further analysis. With the `mine` commands, an algorithm is required to process the data in the `games` table. Please note the difference with the seed commands, which are meant for loading the tables with data.

## `heuristics_mine`

This column is intended to store a JSON object representing the heuristics in a game. It allows to gather insights about the decisions that have been made to make the moves. The example below populates the `heuristics_mine` column with heuristics data on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```

## `fen_mine`

This column is intended to store a text string of comma-separated values representing the chess positions in a game. It allows to search games by piece placement in FEN format. The example below populates the `fen_mine` column with chess positions in FEN format on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/fen.php "Anand,V"
```
