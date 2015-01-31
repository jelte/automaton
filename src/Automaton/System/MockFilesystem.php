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

    public function remove($dirs)
    {
        $this->output->writeln(sprintf('%s(%s)',__FUNCTION__,$dirs));
    }

    public function mirror($originDir, $targetDir, \Traversable $iterator = null, $options = array())
    {
        $this->output->writeln(sprintf('%s(%s, %s)',__FUNCTION__,$originDir, $targetDir));
    }

    public function __call($name, $arguments)
    {
        $this->output->writeln(sprintf('%s',$name));
    }

    public function exists($path)
    {
        $this->output->writeln(sprintf('%s(%s)',__FUNCTION__,$path));
    }

    public function mkdir($dirs, $mode = 0777)
    {
        $this->output->writeln(sprintf('%s(%s, %s)',__FUNCTION__, $dirs, $mode));
    }
}
