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
        // TODO: Implement upload() method.
    }

    public function download($remote, $local)
    {
        // TODO: Implement download() method.
    }

    public function mkdir($path)
    {
        return $this->session->exec("mkdir -p {$path}");
    }

}