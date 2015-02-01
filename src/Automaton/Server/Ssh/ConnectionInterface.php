<?php


namespace Automaton\Server\Ssh;


use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConnectionInterface
{
    public function run($command);

    public function runInteractively($command, $inputLine, $endline, InputInterface $input, OutputInterface $output, HelperSet $helperSet);

    public function upload($local, $remote);

    public function download($remote, $local);

    public function mkdir($path);
}
