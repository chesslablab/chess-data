# Chess Data

✨ [Chess Data](https://github.com/chesslablab/chess-data) is the simplest yet powerful MySQL chess database to learn and play chess online.

## Installation

Clone the `chesslablab/chess-data` repo into your projects folder. Then `cd` the `chess-data` directory and create an `.env` file:

```txt
cp .env.example .env
```

Run the Docker container in detached mode in the background:

```txt
docker compose up -d
```

Update the `.env` file to your specific needs and create the `chess` database:

```text
docker exec -itu 1000:1000 chess_data_php php cli/db-create.php
```

## Bootstrapping

The chess database consists of these tables: `annotations`, `games`, `openings` and `users`.

```text
mysql> show tables;
+-----------------+
| Tables_in_chess |
+-----------------+
| annotations     |
| games           |
| openings        |
| users           |
+-----------------+
4 rows in set (0.00 sec)
```

### 🗒 `annotations`

This table is seeded with the CSV files in the `data/annotations` folder.

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/annotations.php data/annotations
```

### 🗒 `games`

This table can be seeded with the PGN files in the `data/example` folder either all at once or file by file as it is shown in the examples below.

Seed the `games` table with all the files in the `data/example` folder:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/example
✓ 3444 games out of a total of 3444 are OK.
✓ 1825 games out of a total of 1825 are OK.
✓ 5662 games out of a total of 5662 are OK.
✓ 1346 games out of a total of 1346 are OK.
✓ 597 games out of a total of 597 are OK.
✓ 2128 games out of a total of 2128 are OK.
✓ 4646 games out of a total of 4646 are OK.
✓ 4144 games out of a total of 4144 are OK.
✓ 1218 games out of a total of 1218 are OK.
✓ 3878 games out of a total of 3878 are OK.
✓ 2275 games out of a total of 2275 are OK.
✓ 827 games out of a total of 827 are OK.
```

Seed the `games` table file by file:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/example/Anand.pgn
✓ 4144 games out of a total of 4144 are OK.
```

The `games` table can also be seeded with your own set of PGN files in the `data/games` folder:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/games
```

Please note that all files in the `data` folder are gitignored except those in `data/example`. The chess games won't be loaded into the database if containing PGN tags other than the ones supported by the tables created in the [cli/db-create.php](https://github.com/chesslablab/chess-data/blob/main/cli/db-create.php) script. If that is the case you may want to remove the unsupported tags as shown in the example below.

```text
find . -name '*.pgn' -print0 | xargs -0 sed -i "/\[PlyCount .*\]/d"
```

### 🗒 `openings`

This table is seeded with the CSV files in the `data/openings` folder.

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/openings.php data/openings
```

### 🗒 `users`

This is how to seed the `users` table with fake random generated usernames:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/users.php 5000
```

## Data Mining

Data mining provides an additional boost to the SQL queries that can be performed on the `games` table. The precondition for it is to seed the `games` table with data. The CLI commands described below are to populate the columns suffixed with the word `_mine` with pre-calculated data for further analysis.

### CLI Commands

#### `fen.php`

The example below populates the `fen_mine` column with chess positions in FEN format on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/fen.php "Anand,V"
```

This column is intended to store a text string of comma-separated values representing the chess positions in a game. It allows to search games by piece placement in FEN format.

##### Example

Fetch all games matching a particular position.

```sql
SELECT
  *
FROM
  games
WHERE
  fen_mine LIKE '%r1bqkbnr/pp1ppppp/2n5/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R%';
```

#### `heuristics.php`

The example below calculates the `heuristics_mine` column on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```

The `heuristics_mine` column is intended to store a JSON object representing the [PHP Chess heuristics](https://php-chess.chesslablab.org/heuristics/) in a game. It allows to gather insights about the decisions that have been made to make the moves. With this data, you can take advantage of [MySQL JSON functions](https://dev.mysql.com/doc/refman/8.0/en/json-functions.html) to perform operations on JSON values like in the following examples.

##### Example

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

- [Chess\Eval\FastFunction](https://github.com/chesslablab/php-chess/blob/main/src/Eval/FastFunction.php)

Thus, `[0]` corresponds to the material evaluation in the fast function array.

##### Example

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

##### Example

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

##### Example

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

### MySQL User-Defined Functions

What makes a game won or lost? What are the most relevant heuristics? How does one player's style differ from another's? What are the features that make Stockfish stand out? Is there any difference between games with the black pieces and games with the white pieces?

MySQL user-defined functions can help answer these questions!

#### `SCORE()`

Score of a heuristic by result. Similarly to the Steinitz evaluation of a chess position, the score of a heuristic is the difference between the positive and negative values in the array. The score of a heuristic is an accurate indicator of an advantage. If the result is positive, the player with the white pieces has had the advantage, while if it is negative, it is the player with the black pieces who has had it.

##### `res`

The result of the game.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS SCORE//
CREATE FUNCTION SCORE(res VARCHAR(7), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
            SUM(CASE WHEN value > 0 THEN 1 ELSE 0 END) - SUM(CASE WHEN value < 0 THEN 1 ELSE 0 END) AS diff
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT SCORE('1-0', 1);
```

#### `MEAN()`

The mean of a heuristic by result is just another way of looking at the data. If the result is positive, the player with the white pieces has had the advantage, while if negative, it is the player with the black pieces who has had it.

##### `res`

The result of the game.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS MEAN//
CREATE FUNCTION MEAN(res VARCHAR(7), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT MEAN('1-0', 1);
```

#### `SCORE_W()`

Score of the given heuristic for the player who has won with the white pieces.

##### `player`

The name of the player.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS SCORE_W//
CREATE FUNCTION SCORE_W(player VARCHAR(32), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
          AND White = player
          AND Result = "1-0";
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;     
    OPEN cur;
    label:LOOP
        FETCH cur INTO heuristic;
        IF done = 1 THEN
            LEAVE label;
        END IF;
        SELECT
          SUM(CASE WHEN value > 0 THEN 1 ELSE 0 END) - SUM(CASE WHEN value < 0 THEN 1 ELSE 0 END) AS diff
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT SCORE_W("Anand,V", 1);
```

#### `SCORE_B()`

Score of the given heuristic for the player who has won with the black pieces.

##### `player`

The name of the player.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS SCORE_B//
CREATE FUNCTION SCORE_B(player VARCHAR(32), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
          AND Black = player
          AND Result = "0-1";
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;     
    OPEN cur;
    label:LOOP
        FETCH cur INTO heuristic;
        IF done = 1 THEN
            LEAVE label;
        END IF;
        SELECT
          SUM(CASE WHEN value > 0 THEN 1 ELSE 0 END) - SUM(CASE WHEN value < 0 THEN 1 ELSE 0 END) AS diff
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT SCORE_B("Anand,V", 1);
```

#### `MEAN_W()`

The mean of the given heuristic for the player who has won with the white pieces.

##### `player`

The name of the player.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS MEAN_W//
CREATE FUNCTION MEAN_W(player VARCHAR(32), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
          AND White = player
          AND Result = "1-0";
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT MEAN_W("Anand,V", 1);
```

#### `MEAN_B()`

The mean of the given heuristic for the player who has won with the black pieces.

##### `player`

The name of the player.

##### `i`

The index of the PHP Chess evaluation feature in the evaluation function.

```sql
DELIMITER //
DROP FUNCTION IF EXISTS MEAN_B//
CREATE FUNCTION MEAN_B(player VARCHAR(32), i INT) RETURNS FLOAT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE count INT DEFAULT 0;
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
          AND Black = player
          AND Result = "0-1";
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
    RETURN total / count;
END//
DELIMITER ;
```
```text
SELECT MEAN_B("Anand,V", 1);
```
