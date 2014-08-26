<?php


namespace Automaton\Server;


use Automaton\Server\Ssh\Configuration;
use Automaton\Server\Ssh\ConnectionInterface;
use Automaton\Server\Ssh\PhpSecLib;
use Automaton\Utils\Uri;

class SshServer extends AbstractServer
{
    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        return $this->connection->run($command);
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        return $this->connection->upload($local, $remote);
    }
}
