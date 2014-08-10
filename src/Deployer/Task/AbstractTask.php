<?php


namespace Deployer\Task;


abstract class AbstractTask implements TaskInterface
{
    protected $name;

    protected $description;

    protected $before = [];

    protected $after = [];

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function desc($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function before(TaskInterface $task)
    {
        $this->before[] = $task;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function after(TaskInterface $task)
    {
        $this->after[] = $task;
    }

    public function getAfter()
    {
        return $this->after;
    }
}