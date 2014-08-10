<?php


namespace Deployer\Exception;


class PluginNotFoundException extends \Exception {

    private $message = 'Plugin "%s" not defined';

    public function __construct($name, $code = 0, $previous = null)
    {
        parent::__construct(sprintf($this->message, $name), $code, $previous);
    }
} 