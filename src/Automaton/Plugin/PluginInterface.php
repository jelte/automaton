<?php


namespace Automaton\Plugin;


interface PluginInterface {
    public function get($name);

    public function all();
} 