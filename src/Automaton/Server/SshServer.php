<?php


namespace Automaton\Server;


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
    public function upload($local, $remote)
    {
        $this->debug("upload {$local} to {$remote}");
        return $this->connection->upload($local, $remote);
    }
}
