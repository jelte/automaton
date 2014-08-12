<?php


namespace Automaton\Task;


use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\RuntimeEnvironment;
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
                $this->onInvoke(new TaskEvent($subTask,$runtimeEnvironment));
            }
        } elseif ($task instanceof AliasInterface) {
            $this->onInvoke(new TaskEvent($task->getOriginal(),$runtimeEnvironment));
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
            $args[] = $runtimeEnvironment->get($parameter->getName());
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