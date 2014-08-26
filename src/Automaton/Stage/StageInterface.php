<?php


namespace Automaton\Stage;


interface StageInterface
{
    public function getName();

    public function getServers();

    public function get($name, $default = null);
}
