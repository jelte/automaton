<?php


namespace Deployer\Server;

use Deployer\Exception\InvalidArgumentException;
use Deployer\Plugin\AbstractPlugin;
use Deployer\Utils\Uri;

class ServerPlugin extends AbstractPlugin
{
    protected $serverTypes;

    /**
     * @param array $serverTypes
     */
    public function __construct(array $serverTypes = array())
    {
        $this->serverTypes = $serverTypes;
    }

    /**
     * @param $name
     * @param $uri
     * @return mixed
     */
    public function server($name, $uri)
    {
        if (!($uri instanceof Uri)) {
            $uri = new Uri($uri);
        }

        if (array_key_exists($uri->getScheme(), $this->serverTypes)) {
            return $this->registerInstance($name, new $this->serverTypes[$uri->getScheme()]($name, $uri));
        }

        throw new InvalidArgumentException('No server handler defined for ' . $uri->getScheme());
    }
}