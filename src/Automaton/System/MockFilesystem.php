<?php


namespace Automaton\System;


use Symfony\Component\Console\Output\OutputInterface;

class MockFilesystem implements FilesystemInterface
{
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __call($name, $arguments)
    {
        $this->output->writeln(sprintf('%s %s',$name,implode(' ', $arguments)));
    }
} 