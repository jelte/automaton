<?php


namespace Automaton\Server\Ssh;


use Automaton\Ssh2\Session;

class SshConnection implements ConnectionInterface
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function run($command)
    {
        return $this->session->exec($command);
    }

    public function upload($local, $remote)
    {
        return $this->session->upload($local, $remote);
    }

    public function download($remote, $local)
    {
        throw new \DomainException('Not yet supported');
    }

    public function mkdir($path)
    {
        return $this->session->exec("mkdir -p {$path}");
    }

}
