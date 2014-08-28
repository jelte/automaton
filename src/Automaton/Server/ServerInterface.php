<?php


namespace Automaton\Server;


use Symfony\Component\Console\Output\OutputInterface;

interface ServerInterface {
    public function getName();

    public function run($command);

    public function cwd($path);

    public function upload($local, $remote);

    public function setOutput(OutputInterface $output = null);
}
