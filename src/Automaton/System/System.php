<?php


namespace Automaton\System;

use Symfony\Component\Process\Process;

class System implements SystemInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    protected $cwd;

    public function __construct(FilesystemInterface $filesystem, $cwd = null)
    {
        $this->filesystem = $filesystem;
        $this->cwd = null === $cwd?getcwd():$cwd;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param $command
     * @return string
     * @codeCoverageIgnore Needs refactoring to be testable.
     */
    public function run($command)
    {
        $process = new Process($command, $this->cwd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
