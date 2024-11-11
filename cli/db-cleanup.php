<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use ChessData\Pdo;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DbCleanupCli extends CLI
{
    const DELETE = [
        'DELETE FROM games WHERE White="Andreikin,Dmitry:Muzychuk,Mariya" OR Black="Andreikin,Dmitry:Muzychuk,Mariya"',
        'DELETE FROM games WHERE White="Caruana,Fabiano:Green,Lee" OR Black="Caruana,Fabiano:Green,Lee"',
        'DELETE FROM games WHERE White="Giri,Anish:Vujatovic,Rajko" OR Black="Giri,Anish:Vujatovic,Rajko"',
        'DELETE FROM games WHERE White="Ivanishin,Anatoly :Ivan Vagner" OR Black="Ivanishin,Anatoly :Ivan Vagner"',
        'DELETE FROM games WHERE White="Jones,Gawain C B:Woodward,Clive" OR Black="Jones,Gawain C B:Woodward,Clive"',
        'DELETE FROM games WHERE White="Kramnik,Vladimir:Picot,Russell" OR Black="Kramnik,Vladimir:Picot,Russell"',
        'DELETE FROM games WHERE White="Nakamura,Hikaru:Hodgson,Jeremy" OR Black="Nakamura,Hikaru:Hodgson,Jeremy"',
        'DELETE FROM games WHERE White="Short,Nigel D:Baptie,Justin" OR Black="Short,Nigel D:Baptie,Justin"',
        'DELETE FROM games WHERE White="Xiangzhi,Bu:Xue Zhao" OR Black="Xiangzhi,Bu:Xue Zhao"',
        'DELETE FROM games WHERE White="Yang,Wen:Yifan,Hou" OR Black="Yang,Wen:Yifan,Hou"',
        'DELETE FROM games WHERE White="Kasparov & Short" OR Black="Kasparov & Short"',
        'DELETE FROM games WHERE White="Carlsen,6" OR Black="Carlsen,6"',
        'DELETE FROM games WHERE Event="?"',
    ];

    protected Pdo $pdo;

    public function __construct()
    {
        parent::__construct();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $conf = include(__DIR__ . '/../config/database.php');

        $this->pdo = Pdo::getInstance($conf);
    }

    protected function setup(Options $options)
    {
        $options->setHelp('Cleans up the chess database.');
    }

    protected function main(Options $options)
    {
        foreach (self::DELETE as $sql) {
            $this->pdo->query($sql);
        }
    }
}

$cli = new DbCleanupCli();
$cli->run();
