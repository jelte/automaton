<?php


namespace Automaton\System;


interface FilesystemInterface
{
    public function exists($path);

    public function mkdir($dirs, $mode = 0777);

    public function remove($dirs);

    public function mirror($originDir, $targetDir, \Traversable $iterator = null, $options = array());
}
