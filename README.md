## PGN Chess for PHP

This is a simple, friendly, and powerful PGN (Portable Game Notation) library for running chess games from within PHP applications. It understands the rules of chess, and is capable of validating and playing PGN notated games.

PGN Chess comes to the rescue in the following scenarios:

- Develop APIs on the server side for validating chess games
- Create funny, random games for fun
- Analyze games of chess
- Build chess-related web apps
- Play chess moves

### 1. Usage

The following example shows how to use PGN Chess.

`pgn-chess/examples/game01.php`

```php
<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = [
    'e4 e5',
    'f4 exf4',
    'd4 Nf6',
    'Nc3 Bb4',
    'Bxf4 Bxc3+',
    'bxc3 Nxe4',
    'Qe2 d5',
    'c4 O-O',
    'Nf3 Nc3',
    'Qd3 Re8+',
    'Kd2 Ne4+',
    'Kc1 Nf2'
];

$board = new Board;

foreach ($game as $entry)
{
    $moves = explode(' ', $entry);
    try
    {
        if ($board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $moves[0])))
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, illegal move." . PHP_EOL;
            exit;
        }
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, illegal move." . PHP_EOL;
            exit;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
```

As you see, the call to the `$board->play` method returns `true` or `false` depending on whether or not a chess move can be run on the board. It is up to you to process the result accordingly. In this particular example we're printing the output and exiting the game if an illegal chess move is found.

This is the output obtained:

    $ php game01.php
    w played e4, OK...
    b played e5, OK...
    w played f4, OK...
    b played exf4, OK...
    w played d4, OK...
    b played Nf6, OK...
    w played Nc3, OK...
    b played Bb4, OK...
    w played Bxf4, OK...
    b played Bxc3+, OK...
    w played bxc3, OK...
    b played Nxe4, OK...
    w played Qe2, OK...
    b played d5, OK...
    w played c4, illegal move.

Let's look at another example where all moves are valid.

`pgn-chess/examples/game02.php`

```php
<?php
use PGNChess\Board;
use PGNChess\PGN;

require_once __DIR__ . '/../vendor/autoload.php';

$game = [
    'e4 c5',
    'Nf3 Nc6',
    'd4 cxd4',
    'Nxd4 Nf6',
    'Nc3 e5',
    'Ndb5 d6',
    'Bg5 a6',
    'Na3 b5',
    'Nd5 Be7',
    'Bxf6 Bxf6',
    'c3 O-O',
    'h4 Rb8',
    'Nc2 Be7',
    'Nce3 Be6',
    'Qf3 Qd7',
    'Rd1 Bd8'
];

$board = new Board;

foreach ($game as $entry)
{
    $moves = explode(' ', $entry);
    try
    {
        if ($board->play(PGN::objectizeMove(PGN::COLOR_WHITE, $moves[0])))
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_WHITE . " played {$moves[0]}, illegal move." . PHP_EOL;
            exit;
        }
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, OK..." . PHP_EOL;
        }
        else
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, illegal move." . PHP_EOL;
            exit;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
```
This time the output is as follows:

    $ php game02.php
    w played e4, OK...
    b played c5, OK...
    w played Nf3, OK...
    b played Nc6, OK...
    w played d4, OK...
    b played cxd4, OK...
    w played Nxd4, OK...
    b played Nf6, OK...
    w played Nc3, OK...
    b played e5, OK...
    w played Ndb5, OK...
    b played d6, OK...
    w played Bg5, OK...
    b played a6, OK...
    w played Na3, OK...
    b played b5, OK...
    w played Nd5, OK...
    b played Be7, OK...
    w played Bxf6, OK...
    b played Bxf6, OK...
    w played c3, OK...
    b played O-O, OK...
    w played h4, OK...
    b played Rb8, OK...
    w played Nc2, OK...
    b played Be7, OK...
    w played Nce3, OK...
    b played Be6, OK...
    w played Qf3, OK...
    b played Qd7, OK...
    w played Rd1, OK...
    b played Bd8, OK...

### 2. TODO tasks

PGN Chess is almost finished, so please be patient and stay curious in regards to the new updates. Thank you!

Here is the list of chess rules to be added:

- When castling kings, make sure that the castling rook exists
