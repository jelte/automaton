<?php


namespace Automaton\Server\Ssh;

use Automaton\Utils\Uri;

class PhpSecLibConnection implements ConnectionInterface
{
    /**
     * @var \Net_SFTP
     */
    private $session;

    /**
     * @var Uri
     */
    protected $uri;

    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(\Net_SFTP $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    private function authenticate()
    {
        if (null !== $this->configuration->getPrivateKey()) {
            $key = new \Crypt_RSA();
            $key->setPassword($this->configuration->getPassPhrase());
            $key->loadKey(file_get_contents($this->configuration->getPrivateKey()));
            $this->session->login($this->uri->getLogin(), $key);
        } elseif (null !== $this->uri->getPassword()) {
            $this->session->login($this->uri->getLogin(), $this->uri->getPassword());
        } elseif (null !== $this->configuration->getPemFile()) {
            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents($this->configuration->getPemFile()));
            $this->session->login($this->uri->getLogin(), $key);
        } elseif (null === $this->uri->getPassword() ) {
            $this->session->login($this->uri->getLogin());
        } else {
            throw new \RuntimeException('Authentication information missing.');
        }
    }

    /**
     * Check if not connected and connect.
     */
    public function session()
    {
        if (null === $this->session) {
            $this->session = $this->createSession();
        }
        return $this->session;
    }

    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        $session = $this->session();
        $result = $session->exec($command);
        if ($session->getStdError()) {
            throw new \RuntimeException($session->getStdError());
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        $session = $this->session();
        if (!$session->put($remote, $local, NET_SFTP_LOCAL_FILE)) {
            throw new \RuntimeException(implode($session->getSFTPErrors(), "\n"));
        }
    }

    public function mkdir($path)
    {
        $this->session()->mkdir($path, -1, true);
    }

    /**
     * {@inheritdoc}
     */
    public function download($local, $remote)
    {
        $session = $this->session();
        if (!$session->get($remote, $local)) {
            throw new \RuntimeException(implode($session->getSFTPErrors(), "\n"));
        }
    }
} 