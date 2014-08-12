<?php


namespace Automaton\System;


use Symfony\Component\Console\Output\OutputInterface;

class DryRunSystem implements SystemInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->filesystem = new MockFilesystem($output);
        $this->output = $output;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }


    public function run($command)
    {
        $this->output->writeln($command);
    }
} 