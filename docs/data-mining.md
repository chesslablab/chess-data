# Data Mining

The following commands are to populate the columns prefixed with the word `mine_` with pre-calculated data for further analysis. With the `mine` commands, an algorithm is required to process the data in the database tables. Please note the difference with the seed commands which are meant for loading the tables with data. Listed below are some examples of commands to populate the `mine_` columns with pre-calculated data.

Populate the `heuristics_mine` column in the `games` table with heuristics data on a player basis:

```text
docker exec -itu 1000:1000 chess_data_php php cli/mine/heuristics.php "Anand,V"
```
