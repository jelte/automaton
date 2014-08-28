<?php


namespace Automaton\System;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class System implements SystemInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    protected $cwd;

    protected $output;

    public function __construct(FilesystemInterface $filesystem, $cwd = null)
    {
        $this->filesystem = $filesystem;
        $this->cwd = null === $cwd?getcwd():$cwd;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }

    public function getTempDir()
    {
        return sys_get_temp_dir();
    }

    /**
     * @param $command
     * @return string
     * @codeCoverageIgnore Needs refactoring to be testable.
     */
    public function run($command)
    {
        $this->debug($command);
        $process = new Process($command, $this->cwd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    protected function debug($command)
    {
        if (null !== $this->output && $this->output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $this->output->writeln("<info>DEBUG</info> [{$time}][local]$ {$command}");
        }
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }
}
