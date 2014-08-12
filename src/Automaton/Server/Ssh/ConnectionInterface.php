<?php


namespace Automaton\Server\Ssh;


use Automaton\Utils\Uri;

interface ConnectionInterface {

    public function init(Uri $uri, Configuration $configuration);

    public function isInitialized();

    public function run($command);

    public function upload($local, $remote);

    public function download($remote, $local);

    public function mkdir($path);
} 