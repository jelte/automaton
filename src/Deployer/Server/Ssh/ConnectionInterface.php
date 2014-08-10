<?php


namespace Deployer\Server\Ssh;


use Deployer\Utils\Uri;

interface ConnectionInterface {

    public function init(Uri $uri, Configuration $configuration);

    public function isInitialized();

    public function run($command);

    public function upload($local, $remote);

    public function download($remote, $local);

    public function mkdir($path);
} 