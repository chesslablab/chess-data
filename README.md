## PGN Chess for PHP

This is a simple, friendly, and powerful PGN (Portable Game Notation) library for running chess games from within PHP applications. It understands the rules of chess, and is capable of validating and playing PGN notated games.

PGN Chess comes to the rescue in the following scenarios:

- Develop APIs on the server side for validating chess games
- Create funny, random games for fun
- Analyze games of chess
- Build chess-related web apps
- Play chess moves

### 1. Usage

The following example shows how to use PGN Chess:

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
        if ($board->play(PGN::objectizeMove(PGN::COLOR_BLACK, $moves[1])))
        {
            echo PGN::COLOR_BLACK . " played {$moves[1]}, OK..." . PHP_EOL;
        }
    }
    catch (\InvalidArgumentException $e)
    {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }
}
```

This is the output obtained for this particular game:

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
    b played O-O, OK...
    w played Nf3, OK...
    b played Nc3, OK...
    w played Qd3, OK...
    w played Kd2, OK...
    b played Ne4+, OK...
    w played Kc1, OK...
    b played Nf2, OK...

### 2. TODO tasks

PGN Chess is almost finished, so please be patient and stay curious in regards to the new updates. Thank you!

Here is the list of chess rules to be added:

- Force the king to be moved when it is in check
- When castling kings, make sure that the castling rook exists
