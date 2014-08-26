<?php


namespace Automaton\System;


interface SystemInterface {
    public function run($command);

    public function getFilesystem();
}
