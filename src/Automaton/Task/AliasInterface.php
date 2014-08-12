<?php


namespace Automaton\Task;


interface AliasInterface extends TaskInterface {
    public function getOriginal();
} 