<?php


namespace Automaton\Server;


use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ServerInterface {
    public function getName();

    public function run($command);

    public function runInteractively($command, $inputLine, $lastLine, OutputInterface $output, HelperSet $helperSet);

    public function cwd($path);

    public function upload($local, $remote);

    public function setOutput(OutputInterface $output = null);
}
