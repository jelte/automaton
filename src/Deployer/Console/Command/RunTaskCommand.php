<?php


namespace Deployer\Console\Command;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Deployer\Console\Command\Event\TaskEvent;
use Deployer\Runner\Runner;
use Deployer\RuntimeEnvironment;
use Deployer\Task\TaskInterface;
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

    protected function configure()
    {
        $this->eventDispatcher->dispatch('deployer.task_command.configure', new TaskCommandEvent($this));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runtimeEnvironment = new RuntimeEnvironment($input, $output);

        $this->eventDispatcher->dispatch('deployer.task.pre_run', new TaskEvent($this->task, $runtimeEnvironment));

        $this->eventDispatcher->dispatch('deployer.task.run', new TaskEvent($this->task, $runtimeEnvironment));

        $this->eventDispatcher->dispatch('deployer.task.post_run', new TaskEvent($this->task, $runtimeEnvironment));
    }
} 