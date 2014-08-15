<?php


namespace Automaton\Console\Command\Event;


use Automaton\RuntimeEnvironment;
use Automaton\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

class TaskEvent extends Event
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var RuntimeEnvironment
     */
    protected $runtimeEnvironment;

    public function __construct(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        $this->task = $task;
        $this->runtimeEnvironment = $runtimeEnvironment;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return RuntimeEnvironment
     */
    public function getRuntimeEnvironment()
    {
        return $this->runtimeEnvironment;
    }
}