# Data Mining

Data mining provides an additional boost to the SQL queries that can be performed on the `games` table. The precondition for data mining is to seed the `games` table with data.

An algorithm is required to process the data in the `games` table. The CLI commands described below are to populate the columns suffixed with the word `_mine` with pre-calculated data for further analysis. The algorithm used to mine data may be more or less time-consuming.

| Diamonds | Description |
| :------- | :---------- |
| 💎 | The data mining command is a little time-consuming |
| 💎💎 | The data mining command is not too time-consuming  |
| 💎💎💎 | The data mining command is time-consuming |

Please note the difference with the seed commands, which are meant for loading the tables with data.

## 💎 `fen.php`

The example below populates the `fen_mine` column with chess positions in FEN format on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/fen.php "Anand,V"
```

This column is intended to store a text string of comma-separated values representing the chess positions in a game. It allows to search games by piece placement in FEN format.

## 💎💎💎 `heuristics.php`

The example below populates the `heuristics_mine` column with heuristics data on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```

This column is intended to store a JSON object representing the heuristics in a game. It allows to gather insights about the decisions that have been made to make the moves. With the data from the heuristics mine, you can take advantage of [MySQL JSON functions](https://dev.mysql.com/doc/refman/8.0/en/json-functions.html) to perform operations on JSON values like in the following examples.

### Example 1

Fetch the material evaluation in all games won by Anand with the white pieces.

```sql
SELECT
  JSON_EXTRACT(heuristics_mine, '$[0]')
FROM
  games
WHERE
  heuristics_mine IS NOT NULL
  AND White = "Anand,V"
  AND Result = '1-0';
```

The index in the second parameter of the `JSON_EXTRACT` function `$[0]` corresponds to the index of the PHP Chess function being used in the [cli/mine/heuristics.php](https://github.com/chesslablab/chess-data/blob/main/cli/mine/heuristics.php) script.

See:

- [Chess\Function\FastFunction](https://github.com/chesslablab/php-chess/blob/main/src/Function/FastFunction.php)

Thus, `$[0]` corresponds to the material evaluation in the fast function array.

### Example 2

Fetch the material evaluation for the tenth move (20 plies) in all games won by Anand with the black pieces.

```sql
SELECT
  JSON_EXTRACT(heuristics_mine, '$[0][19]') as Material
FROM
  games
WHERE
  heuristics_mine IS NOT NULL
  AND Black = "Anand,V"
  AND Result = '0-1';
```

### Example 3

Fetch the games won by Anand with the black pieces having a material disadvantage of at least 0.1 in the tenth move.

```sql
SELECT
  movetext,
  JSON_EXTRACT(heuristics_mine, '$[0][19]') as Material
FROM
  games
WHERE
  heuristics_mine IS NOT NULL
  AND Black = "Anand,V"
  AND Result = '0-1'
GROUP BY
  Material,
  movetext
HAVING
  Material >= 0.1;
```

### Example 4

Convert a material evaluation array from JSON to MySQL for further processing.

```sql
SET
  @j = (
    SELECT
      JSON_EXTRACT(heuristics_mine, '$[0]') as Material
    FROM
      games
    WHERE
      heuristics_mine IS NOT NULL
    LIMIT
      1
  );
```

```sql
SELECT
  *
FROM
  JSON_TABLE(
    @j,
    "$[*]" COLUMNS(balance FLOAT PATH "$")
  ) material;
```

### Example 5

Sum all elements in the previous material evaluation array.

```sql
SELECT
  SUM(balance) as Sum
FROM
  JSON_TABLE(
    @j,
    "$[*]" COLUMNS(balance FLOAT PATH "$")
  ) material;
```
