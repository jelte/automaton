<?php


namespace Automaton\Task;


use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Exception\InvalidArgumentException;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\RuntimeEnvironment;

class TaskPluginEventSubscriber extends AbstractPluginEventSubscriber
{
    public function __construct(TaskPlugin $plugin)
    {
        parent::__construct($plugin);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'automaton.task.run' => 'onRun',
            'automaton.task.invoke' => 'onInvoke'
        );
    }

    public function onRun(TaskEvent $taskEvent)
    {
        $this->onInvoke($taskEvent);
    }

    public function onInvoke(TaskEvent $taskEvent)
    {
        $task = $taskEvent->getTask();
        $runtimeEnvironment = $taskEvent->getRuntimeEnvironment();
        $this->before($task, $runtimeEnvironment);

        if ($task instanceof ExecutableTaskInterface) {
            $this->doInvoke($task->getCallable(), $runtimeEnvironment);
        } else if ($task instanceof GroupTaskInterface) {
            foreach ($task->getTasks() as $subTask) {
                $this->onInvoke(new TaskEvent($subTask, $runtimeEnvironment));
            }
        } elseif ($task instanceof AliasInterface) {
            $this->onInvoke(new TaskEvent($task->getOriginal(), $runtimeEnvironment));
        }
        $this->after($task, $runtimeEnvironment);
    }

    /**
     * @param \ReflectionMethod|\ReflectionFunction $callable
     * @param RuntimeEnvironment $runtimeEnvironment
     */
    protected function doInvoke($callable, RuntimeEnvironment $runtimeEnvironment)
    {
        $args = array();
        foreach ($callable->getParameters() as $parameter) {
            $class = $parameter->getClass() ? $parameter->getClass()->getName() : null;
            $allowsNull = $parameter->allowsNull();
            $name = $parameter->getName();
            $value = $runtimeEnvironment->get($name);
            if ($value === null && !$allowsNull) {
                throw new InvalidArgumentException(sprintf('Closure parameter "%s" can not be null', $name));
            }
            if (!$allowsNull && null !== $class && !($value instanceof $class)) {
                throw new InvalidArgumentException(sprintf('Closure parameter "%s" is not an instance of "%s"', $name, $class));
            }
            $args[] = $value;
        }
        $callable->invokeArgs(null, $args);
    }

    protected function before(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getBefore() as $before) {
            $this->onInvoke(new TaskEvent($before, $runtimeEnvironment));
        }
    }

    protected function after(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getAfter() as $after) {
            $this->onInvoke(new TaskEvent($after, $runtimeEnvironment));
        }
    }
}
