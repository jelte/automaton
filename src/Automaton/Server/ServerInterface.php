<?php


namespace Automaton\Server;


interface ServerInterface {
    public function getName();

    public function run($command);

    public function cwd($path);

    public function upload($local, $remote);
}
