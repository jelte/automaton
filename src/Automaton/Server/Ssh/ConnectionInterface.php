<?php


namespace Automaton\Server\Ssh;


interface ConnectionInterface
{
    public function run($command);

    public function upload($local, $remote);

    public function download($remote, $local);

    public function mkdir($path);
}
