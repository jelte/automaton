<?php


namespace Automaton\Task;


use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Exception\InvalidArgumentException;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\RuntimeEnvironment;
use Symfony\Component\Console\Output\OutputInterface;

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
            $this->doInvoke($task, $task->getCallable(), $runtimeEnvironment, $runtimeEnvironment->getOutput());
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
     * @param \ReflectionMethod|\ReflectionFunction|array $callable
     * @param RuntimeEnvironment $runtimeEnvironment
     */
    protected function doInvoke(ExecutableTaskInterface $task, $callable, RuntimeEnvironment $runtimeEnvironment, OutputInterface $output = null)
    {
        $object = null;
        if ( is_array($callable) ) {
            list($object, $method) = $callable;
            $callable = $method;
        }
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
        if ( null !== $output && $output->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
            $runtimeEnvironment->getOutput()->write(str_pad($task->getName(), 40, '.'));
        }
        $callable->invokeArgs($object, $args);
        if ( null !== $output && $output->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
            $runtimeEnvironment->getOutput()->writeln("<info>✔</info>");
        }
    }

    protected function before(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getBefore() as $before) {
            foreach ( $before as $task ) {
                $this->onInvoke(new TaskEvent($task, $runtimeEnvironment));
            }
        }
    }

    protected function after(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getAfter() as $after) {
            foreach ( $after as $task ) {
                $this->onInvoke(new TaskEvent($task, $runtimeEnvironment));
            }
        }
    }
}