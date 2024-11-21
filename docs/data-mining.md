# Data Mining

Data mining provides an additional boost to the SQL queries that can be performed on the `games` table. The precondition for data mining is to seed the `games` table with data.

The CLI commands described below are to populate the columns suffixed with the word `_mine` with pre-calculated data for further analysis. The algorithm used to mine the data may be more or less time-consuming.

| Time | Description |
| :------- | :---------- |
| 💎 | The data mining command is not time-consuming. |
| 💎💎 | The data mining command is not too time-consuming.  |
| 💎💎💎 | The data mining command is time-consuming. |

Please note the difference with the seed commands, which are meant for loading the tables with data.

## CLI Commands

### 💎 `fen.php`

The example below populates the `fen_mine` column with chess positions in FEN format on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/fen.php "Anand,V"
```

This column is intended to store a text string of comma-separated values representing the chess positions in a game. It allows to search games by piece placement in FEN format.

### 💎💎💎 `heuristics.php`

The example below populates the `heuristics_mine` column with heuristics data on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```

This column is intended to store a JSON object representing the [PHP Chess heuristics](https://chesslablab.github.io/php-chess/heuristics/) in a game. It allows to gather insights about the decisions that have been made to make the moves. With the data from the heuristics mine, you can take advantage of [MySQL JSON functions](https://dev.mysql.com/doc/refman/8.0/en/json-functions.html) to perform operations on JSON values like in the following examples.

#### Example

Fetch the material evaluation in all games won by Anand with the white pieces.

```sql
SELECT
  JSON_EXTRACT(heuristics_mine, '$[*][0]') as Material
FROM
  games
WHERE
  heuristics_mine IS NOT NULL
  AND White = "Anand,V"
  AND Result = '1-0';
```

The index in the second parameter of the `JSON_EXTRACT` function `$[*][0]` corresponds to the index of the PHP Chess function being used in the [cli/mine/heuristics.php](https://github.com/chesslablab/chess-data/blob/main/cli/mine/heuristics.php) script.

See:

- [Chess\Function\FastFunction](https://github.com/chesslablab/php-chess/blob/main/src/Function/FastFunction.php)

Thus, `[0]` corresponds to the material evaluation in the fast function array.

#### Example

Convert the material evaluation array of a random game won by Anand with the white pieces from JSON to MySQL.

```sql
SET
  @j = (
    SELECT
      JSON_EXTRACT(heuristics_mine, '$[*][0]') as Material
    FROM
      games
    WHERE
      heuristics_mine IS NOT NULL
      AND White = "Anand,V"
      AND Result = '1-0'
    ORDER BY
      RAND()
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
    "$[*]" COLUMNS(
      id FOR ORDINALITY,
      value INT PATH "$"
    )
  ) material;
```

```text
+------+-------+
| id   | value |
+------+-------+
|    1 |     0 |
|    2 |     0 |
|    3 |     0 |
|    4 |     0 |
|    5 |     0 |
|    6 |     0 |
|    7 |     0 |
|    8 |     0 |
|    9 |     0 |
|   10 |     0 |
|   11 |     0 |
|   12 |     0 |
|   13 |     0 |
|   14 |     0 |
|   15 |     0 |
|   16 |     0 |
|   17 |     0 |
|   18 |     0 |
|   19 |     0 |
|   20 |     0 |
|   21 |     0 |
|   22 |     0 |
|   23 |     0 |
|   24 |     0 |
|   25 |     0 |
|   26 |     0 |
|   27 |     1 |
|   28 |     0 |
|   29 |     0 |
|   30 |     0 |
|   31 |     0 |
|   32 |     0 |
|   33 |     0 |
|   34 |     0 |
|   35 |     0 |
|   36 |     0 |
|   37 |     1 |
|   38 |     0 |
|   39 |     0 |
|   40 |     0 |
|   41 |     0 |
|   42 |     0 |
|   43 |     0 |
|   44 |    -1 |
|   45 |     0 |
|   46 |     0 |
|   47 |     0 |
|   48 |     0 |
|   49 |     0 |
|   50 |     0 |
|   51 |     0 |
|   52 |     0 |
|   53 |     0 |
|   54 |     0 |
|   55 |     1 |
|   56 |     0 |
|   57 |     0 |
|   58 |     0 |
|   59 |     0 |
|   60 |     0 |
|   61 |     0 |
|   62 |     0 |
|   63 |     0 |
|   64 |     0 |
|   65 |     0 |
|   66 |     0 |
|   67 |     0 |
|   68 |     0 |
|   69 |     0 |
|   70 |    -1 |
|   71 |     0 |
|   72 |     0 |
|   73 |     0 |
|   74 |     0 |
|   75 |     0 |
|   76 |     0 |
|   77 |     1 |
|   78 |     0 |
|   79 |     0 |
|   80 |     0 |
|   81 |     0 |
|   82 |     0 |
|   83 |     1 |
|   84 |     0 |
|   85 |     0 |
|   86 |     0 |
+------+-------+
86 rows in set (0.00 sec)
```

#### Example

Sum all elements in the previous material evaluation array.

```sql
SELECT
  SUM(value) as Sum
FROM
  JSON_TABLE(
    @j,
    "$[*]" COLUMNS(value INT PATH "$")
  ) material;
```

```text
+------+
| Sum  |
+------+
|    3 |
+------+
1 row in set (0.01 sec)
```

#### Example

Select the indexes in the previous material evaluation array where White has a material advantage.

```sql
SELECT
  id
FROM
  JSON_TABLE(
    @j,
    "$[*]" COLUMNS(
      id FOR ORDINALITY, value INT PATH "$"
    )
  ) material
WHERE
  value = 1;
```

```text
+------+
| id   |
+------+
|   27 |
|   37 |
|   55 |
|   77 |
|   83 |
+------+
5 rows in set (0.00 sec)
```
