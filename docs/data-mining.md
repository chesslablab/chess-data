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

#### Example

Fetch all games matching a particular position.

```sql
SELECT
  *
FROM
  games
WHERE
  fen_mine LIKE '%r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R%';
```

### 💎💎💎 `heuristics.php`

The example below calculates the `heuristics_mine` column on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```

The `heuristics_mine` column is intended to store a JSON object representing the [PHP Chess heuristics](https://chesslablab.github.io/php-chess/heuristics/) in a game. It allows to gather insights about the decisions that have been made to make the moves. With this data, you can take advantage of [MySQL JSON functions](https://dev.mysql.com/doc/refman/8.0/en/json-functions.html) to perform operations on JSON values like in the following examples.

#### Example

Fetch the material evaluation in all games won by Anand with the white pieces.

```sql
SELECT
  JSON_EXTRACT(heuristics_mine, '$[*][0]') as material
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
      JSON_EXTRACT(heuristics_mine, '$[*][0]')
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
      value FLOAT PATH "$"
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
|   21 |  0.13 |
|   22 | -0.07 |
|   23 | -0.07 |
|   24 | -0.07 |
|   25 | -0.07 |
|   26 | -0.07 |
|   27 | -0.07 |
|   28 | -0.07 |
|   29 | -0.07 |
|   30 | -0.07 |
|   31 | -0.07 |
|   32 | -0.07 |
|   33 | -0.07 |
|   34 | -0.07 |
|   35 | -0.07 |
|   36 | -0.07 |
|   37 | -0.07 |
|   38 | -0.13 |
|   39 | -0.13 |
|   40 | -0.13 |
|   41 |   0.1 |
|   42 | -0.78 |
|   43 | -0.66 |
|   44 |    -1 |
|   45 | -0.66 |
|   46 |    -1 |
|   47 | -0.44 |
|   48 |    -1 |
|   49 | -0.26 |
|   50 | -0.26 |
|   51 |   0.2 |
|   52 |  0.03 |
|   53 |  0.02 |
|   54 |  0.02 |
|   55 |  0.02 |
|   56 |  0.02 |
|   57 |  0.06 |
|   58 | -0.19 |
|   59 |  0.02 |
|   60 |  0.01 |
|   61 |  0.02 |
|   62 |  0.02 |
|   63 |  0.08 |
|   64 | -0.12 |
|   65 |  0.08 |
|   66 |  0.08 |
|   67 |  0.07 |
|   68 |  0.06 |
|   69 |  0.14 |
|   70 |  0.14 |
|   71 |  0.08 |
|   72 |  0.08 |
|   73 |  0.06 |
|   74 | -0.14 |
|   75 | -0.14 |
|   76 | -0.21 |
|   77 | -0.21 |
|   78 |  -0.1 |
|   79 |    -1 |
|   80 | -0.21 |
|   81 | -0.43 |
|   82 | -0.43 |
|   83 |    -1 |
|   84 |    -1 |
|   85 | -0.21 |
|   86 | -0.21 |
|   87 | -0.21 |
|   88 | -0.21 |
|   89 | -0.21 |
|   90 | -0.14 |
|   91 |  0.01 |
|   92 |    -1 |
|   93 | -0.44 |
|   94 | -0.12 |
|   95 |  0.11 |
|   96 | -0.72 |
|   97 |  0.24 |
|   98 |  0.59 |
|   99 |  0.88 |
|  100 |  0.21 |
|  101 |  0.25 |
|  102 |  0.13 |
|  103 |  0.11 |
|  104 |  0.39 |
|  105 |  0.24 |
|  106 |  0.14 |
|  107 |  0.06 |
+------+-------+
107 rows in set (0.00 sec)
```

#### Example

Sum all elements in the previous array.

```sql
SELECT
  ROUND(SUM(value), 2) as sum
FROM
  JSON_TABLE(
    @j,
    "$[*]" COLUMNS(value FLOAT PATH "$")
) material;
```

```text
+--------+
| sum    |
+--------+
| -11.32 |
+--------+
1 row in set (0.00 sec)
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
      id FOR ORDINALITY, value FLOAT PATH "$"
    )
  ) material
WHERE
  value > 0;
```

```text
+------+
| id   |
+------+
|   21 |
|   41 |
|   51 |
|   52 |
|   53 |
|   54 |
|   55 |
|   56 |
|   57 |
|   59 |
|   60 |
|   61 |
|   62 |
|   63 |
|   65 |
|   66 |
|   67 |
|   68 |
|   69 |
|   70 |
|   71 |
|   72 |
|   73 |
|   91 |
|   95 |
|   97 |
|   98 |
|   99 |
|  100 |
|  101 |
|  102 |
|  103 |
|  104 |
|  105 |
|  106 |
|  107 |
+------+
36 rows in set (0.00 sec)
```

## Stored Procedures

### `eval_array_sum()`

Average sum of an evaluation feature given a result.

```sql
DELIMITER //
DROP PROCEDURE IF EXISTS eval_array_sum//
CREATE PROCEDURE eval_array_sum(
    IN res VARCHAR(7),
    IN i INT,
    OUT avg FLOAT)
BEGIN
    DECLARE count INT DEFAULT 0;
    DECLARE sum FLOAT DEFAULT 0;
    DECLARE total FLOAT DEFAULT 0;
    DECLARE done INT DEFAULT 0;
    DECLARE heuristic JSON;
    DECLARE cur CURSOR FOR
        SELECT
          JSON_EXTRACT(heuristics_mine, concat('$[*][', i, ']'))
        FROM
          games
        WHERE
          heuristics_mine IS NOT NULL
          AND Result = res;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;     
    OPEN cur;
    label:LOOP
        FETCH cur INTO heuristic;
        IF done = 1 THEN
            LEAVE label;
        END IF;
        SELECT
          ROUND(SUM(value), 2)
        INTO
          @sum
        FROM
          JSON_TABLE(
            heuristic,
            "$[*]" COLUMNS(value FLOAT PATH "$")
        ) material;

        SET total = total + @sum;
        SET count = count + 1;
    END LOOP label;
    CLOSE cur;
    SET avg = total / count;
END//
DELIMITER ;
```

The example below returns the average sum of the center evaluation of all games won with the white pieces.

```text
CALL eval_array_sum('1-0', 1, @center_avg);
Query OK, 0 rows affected (0.10 sec)

mysql> SELECT ROUND(@center_avg, 2) AS center_avg;
+------------+
| center_avg |
+------------+
|      28.47 |
+------------+
1 row in set (0.00 sec)
```
