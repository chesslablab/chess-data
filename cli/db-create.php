<?php

namespace ChessData\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use Chess\Variant\Classical\PGN\Tag;
use Dotenv\Dotenv;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class DbCreateCli extends CLI
{
    public function __construct()
    {
        parent::__construct();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    protected function setup(Options $options)
    {
        $options->setHelp('Creates the chess database.');
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

        $sql = 'CREATE TABLE users (' .
            ' id mediumint UNSIGNED NOT NULL AUTO_INCREMENT, ' .
            'username VARCHAR(128) NULL, ' .
            'elo smallint NOT NULL DEFAULT 1500, ' .
            'lastLoginAt TIMESTAMP NULL, ' .
            'PRIMARY KEY (id) ' .
        ') ENGINE = InnoDB';

        $pdo->query($sql);

        $sql = 'CREATE TABLE openings (' .
            'eco CHAR(3) NULL, ' .
            'name VARCHAR(512) NULL, ' .
            'movetext VARCHAR(1024) NULL ' .
        ') ENGINE = MyISAM';

        $pdo->query($sql);

        $sql = 'CREATE TABLE games (' .
            Tag::EVENT              . ' CHAR(64) NULL, ' .
            Tag::SITE               . ' CHAR(64) NULL, ' .
            Tag::DATE               . ' CHAR(16) NULL, ' .
            Tag::WHITE              . ' CHAR(32) NULL, ' .
            Tag::BLACK              . ' CHAR(32) NULL, ' .
            Tag::RESULT             . ' CHAR(8) NULL, ' .
            Tag::WHITE_ELO          . ' CHAR(8) NULL, ' .
            Tag::BLACK_ELO          . ' CHAR(8) NULL, ' .
            Tag::ECO                . ' CHAR(8) NULL, ' .
            ' fen_mine TEXT NULL, ' .
            ' heuristics_mine JSON NULL, ' .
            ' movetext  VARCHAR(8192) NOT NULL ' .
        ') ENGINE = MyISAM';

        $pdo->query($sql);

        $sql = 'CREATE TABLE annotations (' .
            Tag::EVENT              . ' CHAR(64) NULL, ' .
            Tag::ROUND              . ' CHAR(4) NULL, ' .
            Tag::SITE               . ' CHAR(64) NULL, ' .
            Tag::DATE               . ' CHAR(16) NULL, ' .
            Tag::WHITE              . ' CHAR(32) NULL, ' .
            Tag::BLACK              . ' CHAR(32) NULL, ' .
            Tag::RESULT             . ' CHAR(8) NULL, ' .
            Tag::WHITE_ELO          . ' CHAR(8) NULL, ' .
            Tag::BLACK_ELO          . ' CHAR(8) NULL, ' .
            Tag::ECO                . ' CHAR(8) NULL, ' .
            ' movetext TEXT NOT NULL ' .
        ') ENGINE = MyISAM';

        $pdo->query($sql);

        unset($pdo);
    }
}

$cli = new DbCreateCli();
$cli->run();
