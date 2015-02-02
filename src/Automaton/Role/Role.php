<?php


namespace Automaton\Role;


use Automaton\Task\TaskInterface;

class Role implements RoleInterface
{
    protected $name;

    protected $servers;

    protected $tasks;

    public function __construct($name, array $servers)
    {
        $this->name = $name;
        $this->servers = $servers;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function registerTask(TaskInterface $task)
    {
        $this->tasks[] = $task->getName();
    }
}
