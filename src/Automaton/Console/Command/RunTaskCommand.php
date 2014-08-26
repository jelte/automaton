<?php


namespace Automaton\Console\Command;


use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\RuntimeEnvironment;
use Automaton\Task\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RunTaskCommand extends Command
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(TaskInterface $task, EventDispatcherInterface $eventDispatcher)
    {
        $this->task = $task;
        $this->eventDispatcher = $eventDispatcher;
        parent::__construct($task->getName());
    }

    public function getDescription()
    {
        return $this->task->getDescription();
    }

    protected function configure()
    {
        $this->eventDispatcher->dispatch('automaton.task_command.configure', new TaskCommandEvent($this));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runtimeEnvironment = new RuntimeEnvironment($input, $output);

        $this->eventDispatcher->dispatch('automaton.task.pre_run', new TaskEvent($this->task, $runtimeEnvironment));

        $this->eventDispatcher->dispatch('automaton.task.run', new TaskEvent($this->task, $runtimeEnvironment));

        $this->eventDispatcher->dispatch('automaton.task.post_run', new TaskEvent($this->task, $runtimeEnvironment));
    }
}
