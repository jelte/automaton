<?php


namespace Automaton\Server;


use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshServer extends AbstractServer
{
    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        $this->debug($command);
        return $this->connection->run($command);
    }

    /**
     * {@inheritdoc}
     */
    public function runInteractively($command, $inputLine, $endline, InputInterface $input, OutputInterface $output, HelperSet $helperSet)
    {
        $this->debug($command);
        return $this->connection->runInteractively($command, $inputLine, $endline, $input, $output, $helperSet);
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        $this->debug("upload {$local} to {$remote}");
        return $this->connection->upload($local, $remote);
    }
}
