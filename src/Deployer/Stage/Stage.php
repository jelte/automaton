<?php


namespace Deployer\Stage;


class Stage implements StageInterface
{
    private $name;

    private $servers;

    private $options;

    public function __construct($name, array $servers, array $options = array())
    {
        $this->name = $name;
        $this->servers = $servers;
        $this->options = $options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function getOptions()
    {
        return $this->options;
    }
} 