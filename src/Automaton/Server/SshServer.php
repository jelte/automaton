<?php


namespace Automaton\Server;


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
