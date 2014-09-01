<?php


namespace Automaton\Server\Ssh;

class PhpSeclibConnection implements ConnectionInterface
{
    /**
     * @var \Net_SFTP
     */
    private $session;

    public function __construct(\Net_SFTP $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        $result = $this->session->exec($command);
        if ($this->session->getStdError()) {
            throw new \RuntimeException($this->session->getStdError());
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        if (!$this->session->put($remote, $local, NET_SFTP_LOCAL_FILE)) {
            throw new \RuntimeException(implode($this->session->getSFTPErrors(), "\n"));
        }
    }

    public function mkdir($path)
    {
        $this->session->mkdir($path, -1, true);
    }

    /**
     * {@inheritdoc}
     */
    public function download($local, $remote)
    {
        if (!$this->session->get($remote, $local)) {
            throw new \RuntimeException(implode($this->session->getSFTPErrors(), "\n"));
        }
    }
}
