<?php


namespace Deployer\Task;

use Deployer\Exception\InvalidArgumentException;
use Deployer\Plugin\AbstractPlugin;

class TaskPlugin extends AbstractPlugin
{
    /**
     * @param string $name
     * @param \Closure|array|string|TaskInterface $callable
     * @param string $description
     * @return TaskInterface
     * @throws InvalidArgumentException
     */
    public function task($name, $callable, $description = null)
    {
        if ($callable instanceof TaskInterface) {
            return $this->registerInstance($name, new Alias($name, $callable));
        }
        if ($callable instanceof \Closure || is_callable($callable)) {
            return $this->registerInstance($name, new Task($name, $description, $callable));
        }
        if (is_array($callable)) {
            $subTasks = [];
            foreach ( $callable as $subTask ) {
                $subTasks[] = $this->get($subTask);
            }
            return $this->registerInstance($name, new GroupTask($name, $description, $subTasks));
        }
        throw new InvalidArgumentException("Can not create a task from the given callable");
    }

    /**
     * @param $name
     * @param $task
     * @return Alias
     * @throws \Deployer\Exception\TaskNotFoundException
     * @throws \Deployer\Exception\InvalidArgumentException
     */
    public function alias($name, $task)
    {
        if (!($task instanceof TaskInterface)) {
            $task = $this->get($task);
        }
        return $this->task($name, $task);
    }

    /**
     * @param $task
     * @param $before
     */
    public function before($task, $before)
    {
        $this->get($task)->before($this->get($before));
    }

    /**
     * @param $task
     * @param $after
     */
    public function after($task, $after)
    {
        $this->get($task)->after($this->get($after));
    }
} 