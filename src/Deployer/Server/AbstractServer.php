<?php


namespace Deployer\Server;


use Deployer\Utils\Uri;

abstract class AbstractServer implements ServerInterface {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Uri
     */
    protected $uri;

    public function __construct($name, Uri $uri)
    {
        $this->name = $name;
        $this->uri = $uri;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUri()
    {
        return $this->uri;
    }
} 