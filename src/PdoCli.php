<?php

namespace ChessData;

use splitbrain\phpcli\Options;

abstract class PdoCli extends AbstractPdoCli
{
    protected function main(Options $options)
    {
        if (is_file($options->getArgs()[0])) {
            $result = $this->seed($options->getArgs()[0]);
            $this->display($result);
        } elseif (is_dir($options->getArgs()[0])) {
            $dir = __DIR__.'/../'.$options->getArgs()[0];
            $dirIterator = new \DirectoryIterator($dir);
            foreach ($dirIterator as $fileinfo) {
                if (!$fileinfo->isDot()) {
                    $result = $this->seed("$dir/{$fileinfo->getFilename()}");
                    $this->display($result);
                }
            }
        }
    }
}
