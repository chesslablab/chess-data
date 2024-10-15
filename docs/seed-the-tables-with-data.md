# Seed the Tables with Data

As a rule of thumb, tables are loaded using the files contained in the `data` folder and can be loaded either all at once or file by file. Listed below are some examples of commands to seed the `games` table with data.

Seed the `games` table with all the files contained in the `data/example` folder:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/example
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
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/example/Anand.pgn
✗ 2 games did not pass the validation.
✓ 4142 games out of a total of 4144 are OK.
```

Seed the `games` table with your own set of files in the `data/games` folder:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/games.php data/games
```

Please note that all files in the `data` folder are gitignored except those contained in `data/example`.

Also, the chess games won't be loaded into the database if containing PGN tags other than the ones supported by the tables created in the [cli/db-create.php](https://github.com/chesslablab/chess-data/blob/main/cli/db-create.php) script. If that is the case you may want to remove the unsupported tags as shown in the example below.

```text
find . -name '*.pgn' -print0 | xargs -0 sed -i "/\[PlyCount .*\]/d"
```

In addition to the `games` table, there are also tables for chess `openings` and `users`.

Seed the `openings` table:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/openings.php data/openings
```

Seed the `users` table with fake random generated usernames:

```text
docker exec -itu 1000:1000 chess_data_php php cli/seed/users.php 5000
```
