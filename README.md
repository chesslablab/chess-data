## PGN Chess Data

<p align="center">
	<img src="https://github.com/programarivm/pgn-chess/blob/master/resources/chess-board.jpg" />
</p>

PGN Chess Data provides you with CLI tools to manage a database of PGN games.

### Set Up

Create an `.env` file:

    cp .env.example .env

Start the server:

    bash/start.sh

Find out your Docker container's IP address:

    docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' pgn_chess_data_php_fpm

### Command Line Interface (CLI)

#### `dbcreate.php`

    php cli/dbcreate.php
    This will remove the current PGN Chess database and the data will be lost.
    Do you want to proceed? (Y/N): y

#### `dbseed.php`

    php cli/dbseed.php data/01_games.pgn
    This will search for valid PGN games in the file.
    Large files (for example 50MB) may take a few seconds to be inserted into the database.
    Do you want to proceed? (Y/N): y
    Good! This is a valid PGN file. 3201 games were inserted into the database.

#### `tomysql.php`

Converts a PGN file into a MySQL `INSERT` statement.

    php cli/tomysql.php data/01_games.pgn > data/01_games.mysql

This is the output generated.

    INSERT INTO games (Event, Site, Date, Round, White, Black, Result, WhiteTitle, BlackTitle, WhiteElo, BlackElo, WhiteUSCF, BlackUSCF, WhiteNA, BlackNA, WhiteType, BlackType, EventDate, EventSponsor, Section, Stage, Board, Opening, Variation, SubVariation, ECO, NIC, Time, UTCTime, UTCDate, TimeControl, SetUp, FEN, Termination, Annotator, Mode, PlyCount, movetext) VALUES ('TCh-FRA Top 12 2018', 'Brest FRA', '2018.05.28', '3.3', 'Dornbusch, Tatiana', 'Feller, Sebastien', '0-1', null, null, '2290', '2574', null, null, null, null, null, null, '2018.05.26', null, null, null, null, null, null, null, 'E11', null, null, null, null, null, null, null, null, null, null, null, '1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5 8.d5 e59.O-O Nbd7 10.b4 a5 11.b5 g6 12.Nb1 Nh5 13.Bh6 Ng7 14.Qc2 Nb6 15.Nbd2 Bd716.Kh1 Qc7 17.Rae1 Rae8 18.Ng1 Bc8 19.f4 exf4 20.Qc3 f6 21.Rxf4 Nd7 22.Bc2Ne5 23.h3 Nf7 24.Bxg7 Kxg7 25.Rf2 Kg8 26.Ngf3 Ne5 27.Ref1 Bd8 28.Kg1 Qg729.Nb3 b6 30.Nbd2 Qh6 31.Nxe5 fxe5 32.Rxf8+ Rxf8 33.Rxf8+ Kxf8 34.a4 Kg735.Bd1 Qh4 36.Kf1 Qf4+ 37.Bf3 h5 38.Ke2 Kh6 39.Qe3 g5 40.Qxf4 exf4 41.e5dxe5 42.d6 Be6 43.Bc6 g4 44.hxg4 hxg4 45.Kd3 Kg5 46.Ke4 Kf6 47.Be8 Bg8 48.Bd7 Be6 49.Bc6 Bf5+ 50.Kd5 f3 51.g3 f2 52.Be8 e4 53.Nf1 Be6+ 54.Kc6 Ke555.Bh5 Kd4 0-1'),('11. KIIT Elite Open 2018', 'Bhubaneswar IND', '2018.05.28', '5.3', 'Nitin, S', 'Amonatov, Farrukh', '0-1', null, null, '2432', '2608', null, null, null, null, null, null, '2018.05.25', null, null, null, null, null, null, null, 'B90', null, null, null, null, null, null, null, null, null, null, null, '1.e4 c5 2.Nf3 d6 3.d4 cxd4 4.Nxd4 Nf6 5.Nc3 a6 6.Be3 e5 7.Nb3 Be7 8.h3 b59.a4 b4 10.Nd5 Nbd7 11.Nxe7 Kxe7 12.Qd2 a5 13.O-O-O Qc7 14.f3 Bb7 15.g4Rhc8 16.Bb5 Bc6 17.Bxc6 Qxc6 18.Bg5 Ra6 19.Qe2 Nb6 20.Bxf6+ gxf6 21.f4 Qc422.Rd3 Nxa4 23.g5 fxg5 24.fxe5 dxe5 25.Rhd1 Rac6 26.R1d2 Nc5 27.Nxc5 Qxc528.Qg4 Kf8 29.Rf3 Kg8 30.Qf5 Rf8 31.Rg3 Rg6 32.Rd5 Qc4 33.Rxg5 b3 34.Rd2bxc2 35.Rxc2 Qd4 36.Rcg2 Rd8 37.Rxg6+ hxg6 38.Rxg6+ Kf8 39.Qf3 Qa4 40.Rg2Qa1+ 41.Kc2 Rc8+ 42.Kd3 Qc1 43.Qf6 Qc4+ 44.Ke3 Qd4+ 45.Kf3 Qd3+ 46.Kg4Qxe4+ 47.Kg3 Qg6+ 48.Qxg6 fxg6 49.Kf3 Kf7 50.Ke4 Kf6 51.Rf2+ Ke6 52.Rg2Rc4+ 53.Ke3 Kf5 54.Rf2+ Rf4 55.Rc2 Rh4 56.Rf2+ Ke6 57.Rf3 Rb4 0-1');

#### `syntax.php`

    php cli/syntax.php data/01_games.pgn
	This will search for syntax errors in the PGN file.
	Large files (for example 50MB) may take a few seconds to be parsed.
	Do you want to proceed? (Y/N): y
	Good! This is a valid PGN file.

### License

The GNU General Public License.

### Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PGN Chess Data Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Many thanks.
