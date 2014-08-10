<?php


namespace Deployer\Runner;


use Deployer\Server\ServerInterface;
use Deployer\Task\AliasInterface;
use Deployer\Task\ExecutableTaskInterface;
use Deployer\Task\GroupTaskInterface;
use Deployer\Task\TaskInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Runner
{
    protected $servers = array();

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function setServers(array $servers)
    {
        $this->servers = $servers;
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function setUp(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function run(TaskInterface $task)
    {
        $this->before($task);

        $this->execute($task);

        $this->after($task);
    }

    protected function execute(TaskInterface $task)
    {
        foreach ($this->servers as $serverName => $server) {
            $this->doExecute($task, $server);
        }
    }

    protected function doExecute(TaskInterface $task, ServerInterface $server)
    {
        if ($task instanceof ExecutableTaskInterface) {
            $this->invoke($task->getCallable(), $this->input, $this->output, $server);
        } else if ($task instanceof GroupTaskInterface) {
            foreach ($task->getTasks() as $subTask) {
                $this->run($subTask);
            }
        } elseif ($task instanceof AliasInterface) {
            $this->run($task->getOriginal());
        }
    }

    /**
     * @param \ReflectionMethod | \ReflectionFunction $callable
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param ServerInterface $server
     */
    private function invoke($callable, InputInterface $input, OutputInterface $output, ServerInterface $server)
    {
        $args = array();
        foreach ($callable->getParameters() as $parameter) {
            $args[] = ${$parameter->getName()};
        }
        $callable->invokeArgs(null, $args);
    }

    protected function before(TaskInterface $task)
    {
        foreach ($task->getBefore() as $before) {
            $this->run($before);
        }
    }

    protected function after(TaskInterface $task)
    {
        foreach ($task->getAfter() as $after) {
            $this->run($after);
        }
    }
} 