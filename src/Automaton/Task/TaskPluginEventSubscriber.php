<?php


namespace Automaton\Task;


use Automaton\Console\Command\Event\InvokeEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Exception\InvalidArgumentException;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\RuntimeEnvironment;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaskPluginEventSubscriber extends AbstractPluginEventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(TaskPlugin $plugin, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($plugin);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'automaton.task.run' => 'onRun',
            'automaton.task.pre_invoke' => 'preInvoke',
            'automaton.task.invoke' => 'onInvoke',
            'automaton.task.do_invoke' => 'doInvoke',
            'automaton.task.post_invoke' => 'postInvoke'
        );
    }

    public function preInvoke(TaskEvent $taskEvent)
    {
        $task = $taskEvent->getTask();
        $runtimeEnvironment = $taskEvent->getRuntimeEnvironment();
        $output = $runtimeEnvironment->getOutput();
        if ($task->showProgress() && null !== $output && $output->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
            $runtimeEnvironment->getOutput()->write(str_pad($task->getName(), 40, '.'));
        }
    }

    public function postInvoke(TaskEvent $taskEvent)
    {
        $task = $taskEvent->getTask();
        $runtimeEnvironment = $taskEvent->getRuntimeEnvironment();
        $output = $runtimeEnvironment->getOutput();
        if ($task->showProgress() && null !== $output && $output->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
            $runtimeEnvironment->getOutput()->writeln("<info>✔</info>");
        }
    }

    public function rollback(TaskEvent $taskEvent)
    {
        $task = $taskEvent->getTask();
        $runtimeEnvironment = $taskEvent->getRuntimeEnvironment();
        $output = $runtimeEnvironment->getOutput();
        if ($task->showProgress() && null !== $output && $output->getVerbosity() !== OutputInterface::VERBOSITY_DEBUG) {
            $runtimeEnvironment->getOutput()->writeln("<error>x</error>");
        }
        $this->onRun(new TaskEvent($this->plugin->get('rollback'), $runtimeEnvironment));
    }

    public function onRun(TaskEvent $taskEvent)
    {
        $task = $taskEvent->getTask();
        $runtimeEnvironment = $taskEvent->getRuntimeEnvironment();
        $this->before($task, $runtimeEnvironment);
        if ($task instanceof ExecutableTaskInterface) {
            try {
                $this->eventDispatcher->dispatch('automaton.task.pre_invoke', new TaskEvent($task, $runtimeEnvironment));
                $this->eventDispatcher->dispatch('automaton.task.invoke', new TaskEvent($task, $runtimeEnvironment));
                $this->eventDispatcher->dispatch('automaton.task.post_invoke', new TaskEvent($task, $runtimeEnvironment));
            } catch ( \RuntimeException $e ) {
                $this->eventDispatcher->dispatch('automaton.task.rollback', new TaskEvent($task, $runtimeEnvironment));
                throw $e;
            }
        } else if ($task instanceof GroupTaskInterface) {
            foreach ($task->getTasks() as $subTask) {
                $this->onRun(new TaskEvent($subTask, $runtimeEnvironment));
            }
        } elseif ($task instanceof AliasInterface) {
            $this->onRun(new TaskEvent($task->getOriginal(), $runtimeEnvironment));
        }
        $this->after($task, $runtimeEnvironment);
    }

    public function onInvoke(TaskEvent $taskEvent)
    {
        $this->eventDispatcher->dispatch('automaton.task.do_invoke', new InvokeEvent($taskEvent->getTask(), $taskEvent->getRuntimeEnvironment()));
    }

    public  function doInvoke(InvokeEvent $invokeEvent)
    {
        $callable = $invokeEvent->getCallable();
        $runtimeEnvironment = $invokeEvent->getRuntimeEnvironment();
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
        $callable->invokeArgs($object, $args);
    }

    protected function before(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getBefore() as $before) {
            foreach ( $before as $task ) {
                $this->onRun(new TaskEvent($task, $runtimeEnvironment));
            }
        }
    }

    protected function after(TaskInterface $task, RuntimeEnvironment $runtimeEnvironment)
    {
        foreach ($task->getAfter() as $after) {
            foreach ( $after as $task ) {
                $this->onRun(new TaskEvent($task, $runtimeEnvironment));
            }
        }
    }
}
