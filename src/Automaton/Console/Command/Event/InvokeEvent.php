<?php


namespace Automaton\Console\Command\Event;


use Automaton\RuntimeEnvironment;
use Automaton\Task\ExecutableTaskInterface;
use Automaton\Task\Task;
use Automaton\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

class InvokeEvent extends TaskEvent
{
    /**
     * @var ExecutableTaskInterface
     */
    protected $task;


    public function __construct(ExecutableTaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        parent::__construct($task, $runtimeEnvironment);
    }

    /**
     * @return \ReflectionFunction|\ReflectionMethod|array
     */
    public function getCallable()
    {
        return $this->task->getCallable();
    }
}
