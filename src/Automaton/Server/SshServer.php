<?php


namespace Automaton\Server;


use Automaton\Server\Ssh\Configuration;
use Automaton\Server\Ssh\ConnectionInterface;
use Automaton\Server\Ssh\PhpSecLib;
use Automaton\Utils\Uri;

class SshServer extends AbstractServer
{
    /** @var \Automaton\Server\Ssh\Configuration */
    private $configuration;

    /** @var ConnectionInterface */
    private $connection;

    /**
     * Array of created directories during upload.
     * @var array
     */
    private $directories = [];

    public function __construct($name, Uri $uri, ConnectionInterface $connection = null, Configuration $configuration = null)
    {
        parent::__construct($name, $uri);
        $this->connection = null === $connection ? new PhpSecLib() : $connection;
        $this->configuration = null === $configuration ? new Configuration() : $configuration;
    }

    public function connection()
    {
        if (!$this->connection->isInitialized()) {
            $this->connection->init($this->uri, $this->configuration);
        }
        return $this->connection;
    }

    public function privateKey($privateKey)
    {
        $this->configuration->setPrivateKey($privateKey);
    }

    public function passPhrase($passPhrase)
    {
        $this->configuration->setPassPhrase($passPhrase);
    }

    /**
     * {@inheritdoc}
     */
    public function run($command)
    {
        return $this->connection()->run($command);
    }

    /**
     * {@inheritdoc}
     */
    public function upload($local, $remote)
    {
        $path = dirname($remote);
        if (!isset($this->directories[$path])) {
            $this->connection()->mkdir($path);
            $this->directories[$path] = true;
        }
        $this->connection()->upload($local, $remote);
    }
}