<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use Chess\PGN\Tag;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DbCreateCli extends CLI
{
    protected function setup(Options $options)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $options->setHelp('Creates the chess database with the games table.');
        $options->registerOption('heuristics', 'Add heuristics for further data visualization.');
    }

    protected function main(Options $options)
    {
        $pdo = new \PDO(
            $_ENV['DB_DRIVER'] . ':host=' . $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $sql = 'DROP DATABASE IF EXISTS ' . $_ENV['DB_DATABASE'];
        $pdo->exec($sql);
        $sql = 'CREATE DATABASE ' . $_ENV['DB_DATABASE'];
        $pdo->exec($sql);
        $sql = 'use ' . $_ENV['DB_DATABASE'];
        $pdo->exec($sql);

        $sql = 'CREATE TABLE games (' .
            ' id mediumint UNSIGNED NOT NULL AUTO_INCREMENT, ' .
            Tag::EVENT                      . ' CHAR(64) NULL, ' . // STR (Seven Tag Roster)
            Tag::SITE                       . ' CHAR(64) NULL, ' .
            Tag::DATE                       . ' CHAR(16) NULL, ' .
            Tag::WHITE                      . ' CHAR(32) NULL, ' .
            Tag::BLACK                      . ' CHAR(32) NULL, ' .
            Tag::RESULT                     . ' CHAR(8) NULL, ' .
            Tag::WHITE_ELO                  . ' CHAR(8) NULL, ' .
            Tag::BLACK_ELO                  . ' CHAR(8) NULL, ' .
            Tag::ECO                        . ' CHAR(8) NULL, ' .
            ' movetext  VARCHAR(3072), ' .
            'PRIMARY KEY (id) ' .
        ') ENGINE = InnoDB';

        $pdo->query($sql);

        if ($options->getOpt('heuristics')) {
            $sql = 'ALTER TABLE games ADD COLUMN `heuristic_picture` JSON';
            $pdo->query($sql);
            $sql = 'ALTER TABLE games ADD COLUMN `heuristic_evaluation` JSON';
            $pdo->query($sql);
        }

        unset($pdo);
    }
}

$cli = new DbCreateCli();
$cli->run();
