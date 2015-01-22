<?php


namespace Automaton\Console\Command\Event;


use Automaton\RuntimeEnvironment;
use Automaton\Task\ExecutableTaskInterface;
use Automaton\Task\Task;
use Automaton\Task\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

class InvokeEvent extends Event
{
    /**
     * @var ExecutableTaskInterface
     */
    protected $task;

    /**
     * @var RuntimeEnvironment
     */
    protected $runtimeEnvironment;


    public function __construct(ExecutableTaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        $this->task = $task;
        $this->runtimeEnvironment = $runtimeEnvironment;
    }

    /**
     * @return ExecutableTaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return \ReflectionFunction|\ReflectionMethod|array
     */
    public function getCallable()
    {
        return $this->task->getCallable();
    }

    /**
     * @return RuntimeEnvironment
     */
    public function getRuntimeEnvironment()
    {
        return $this->runtimeEnvironment;
    }
}
