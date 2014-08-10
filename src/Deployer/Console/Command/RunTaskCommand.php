<?php


namespace Deployer\Console\Command;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Deployer\Runner\Runner;
use Deployer\Task\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RunTaskCommand extends Command
{
    /**
     * @var Runner
     */
    protected $runner;

    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(Runner $runner, TaskInterface $task, EventDispatcherInterface $eventDispatcher)
    {
        $this->runner = $runner;
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
        $this->runner->setUp($input, $output);

        $this->eventDispatcher->dispatch('deployer.runner.pre_run', new RunnerEvent($this->runner, $input, $output));

        $this->runner->run($this->task);

        $this->eventDispatcher->dispatch('deployer.runner.post_run', new RunnerEvent($this->runner, $input, $output));
    }
} 