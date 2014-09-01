<?php


namespace Automaton\Task;


class GroupTask extends AbstractTask implements GroupTaskInterface
{
    protected $tasks;

    public function __construct($name, $description, array $tasks, $public = true, $progress = true)
    {
        parent::__construct($name, $description, $public, $progress);
        $this->tasks = $tasks;
    }

    public function getTasks()
    {
        return $this->tasks;
    }
}
