<?php


namespace Deployer\Server\Ssh;

use Deployer\Utils\Uri;

class PhpSecLib implements ConnectionInterface
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

    public function init(Uri $uri, Configuration $configuration)
    {
        $this->uri = $uri;
        $this->configuration = $configuration;
    }

    public function isInitialized()
    {
        return null !== $this->uri && null !== $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    private function createSession()
    {
        $sftp = new \Net_SFTP($this->uri->getHost(), $this->uri->getPort());

        if (null !== $this->configuration->getPrivateKey()) {
            $key = new \Crypt_RSA();
            $key->setPassword($this->configuration->getPassPhrase());
            $key->loadKey(file_get_contents($this->configuration->getPrivateKey()));
            $sftp->login($this->uri->getLogin(), $key);
        } elseif (null !== $this->uri->getPassword()) {
            $sftp->login($this->uri->getLogin(), $this->uri->getPassword());
        } elseif (null !== $this->configuration->getPemFile()) {
            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents($this->configuration->getPemFile()));
            $sftp->login($this->uri->getLogin(), $key);
        } elseif (null === $this->uri->getPassword() ) {
            $sftp->login($this->uri->getLogin());
        } else {
            throw new \RuntimeException('Authentication information missing.');
        }

        return $sftp;
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