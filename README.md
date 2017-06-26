## PGN Chess for PHP

This is a simple, friendly, and powerful PGN (Portable Game Notation) engine for running chess games from within PHP applications. Since this library can understand the rules of chess, it is capable of validating PGN notated games.

PGN Chess comes to the rescue in the following scenarios:

- Develop APIs on the server side for validating chess games
- Create funny, random games for fun
- Analyze games of chess
- Build chess-related web apps

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

Please note that this application is currently under development and is still being tested, so please be patient and stay curious in regards to the new updates. Thank you.
