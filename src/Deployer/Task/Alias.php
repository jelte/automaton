<?php


namespace Deployer\Task;


class Alias extends AbstractTask implements AliasInterface
{
    protected $original;

    public function __construct($name, TaskInterface $original)
    {
        parent::__construct($name, $original->getDescription());
        $this->original = $original;
    }

    public function getOriginal()
    {
        return $this->original;
    }
}