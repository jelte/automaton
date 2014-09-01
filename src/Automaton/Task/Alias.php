<?php


namespace Automaton\Task;


class Alias extends AbstractTask implements AliasInterface
{
    protected $original;

    public function __construct($name, TaskInterface $original, $public = true, $progress = true)
    {
        parent::__construct($name, $original->getDescription(), $public, $progress);
        $this->original = $original;
    }

    public function getOriginal()
    {
        return $this->original;
    }

    public function getDescription()
    {
        return $this->original->getDescription();
    }
}
