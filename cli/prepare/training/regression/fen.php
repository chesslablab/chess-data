<?php

namespace ChessData\Cli\Prepare\Training\Regression;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Chess\HeuristicsByFenString;
use Chess\Movetext;
use Chess\Player;
use Chess\FEN\StrToBoard;
use Chess\ML\Supervised\Regression\GeometricSumLabeller;
use ChessData\PdoCli;
use splitbrain\phpcli\Options;

/**
 * Prepares the data.
 *
 * Plays chess games starting from a FEN position.
 */
class FenCli extends PdoCli
{
    const DATA_FOLDER = __DIR__.'/../../../../dataset/training/regression';

    protected function setup(Options $options)
    {
        $options->setHelp('Creates a prepared CSV dataset in the dataset/training/regression folder.');
        $options->registerArgument('n', 'A random number of games to be queried.', true);
    }

    protected function main(Options $options)
    {
        $filename = "fen_{$options->getArgs()[0]}_".time().'.csv';

        $sql = "SELECT * FROM endgames
            WHERE FEN IS NOT NULL
            ORDER BY RAND()
            LIMIT {$options->getArgs()[0]}";

        $endgames = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $fp = fopen(self::DATA_FOLDER."/$filename", 'w');

        foreach ($endgames as $endgame) {
            try {
                $board = (new StrToBoard($endgame['FEN']))->create();
                $sequence = (new Movetext($endgame['movetext']))->sequence();
                foreach ($sequence as $movetext) {
                    $wClone = unserialize(serialize($board));
                    $bClone = unserialize(serialize($board));
                    // Black's balance and label
                    $bBoard = (new Player($movetext, $bClone))->play()->getBoard();
                    $bBalance = (new HeuristicsByFenString($bBoard->toFen()))->getResizedBalance(0, 1);
                    $bLabel =  (new GeometricSumLabeller())->label($bBalance);
                    // White's movetext
                    $wMovetext = explode(' ', $movetext);
                    array_splice($wMovetext, -1);
                    $wMovetext = implode(' ', $wMovetext);
                    // White's balance
                    $wBoard = (new Player($wMovetext, $wClone))->play()->getBoard();
                    $wBalance = (new HeuristicsByFenString($wBoard->toFen()))->getResizedBalance(0, 1);
                    // White's balance is labelled with Black's label
                    $row = array_merge($wBalance, [$bLabel]);
                    fputcsv($fp, $row, ';');
                }
            } catch (\Exception $e) {}
        }

        fclose($fp);
    }
}

$cli = new FenCli();
$cli->run();
