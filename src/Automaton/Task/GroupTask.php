<?php


namespace Automaton\Task;


class GroupTask extends AbstractTask implements GroupTaskInterface
{
    protected $tasks;

    public function __construct($name, $description, array $tasks)
    {
        parent::__construct($name, $description);
        $this->tasks = $tasks;
    }

    public function getTasks()
    {
        return $this->tasks;
    }
}