<?php


namespace Automaton\Task;


abstract class AbstractTask implements TaskInterface
{
    protected $name;

    protected $description;

    private $before = [];

    private $after = [];

    protected $public = false;

    protected $progress = true;

    public function __construct($name, $description, $public, $progress)
    {
        $this->name = $name;
        $this->description = $description;
        $this->public = $public;
        $this->progress = $progress;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isPublic()
    {
        return $this->public;
    }

    public function showProgress()
    {
        return $this->progress;
    }

    public function desc($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function before(TaskInterface $task, $priority = 0)
    {
        if ( !isset($this->before[$priority]) ) $this->before[$priority] = array();
        $this->before[$priority][] = $task;
    }

    public function getBefore()
    {
        ksort($this->before);
        return $this->before;
    }

    public function after(TaskInterface $task, $priority = 0)
    {
        if ( !isset($this->after[$priority]) ) $this->after[$priority] = array();
        $this->after[$priority][] = $task;

    }

    public function getAfter()
    {
        ksort($this->after);
        return $this->after;
    }
}
