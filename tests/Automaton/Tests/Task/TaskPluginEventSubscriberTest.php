<?php


namespace Automaton\Tests\Stage;

use Automaton\Stage\StagePluginEventSubscriber;
use Automaton\Task\TaskPluginEventSubscriber;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class TaskPluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $plugin, $task, $runtimeEnvironment, $input, $output, $taskEvent, $eventDispatcher;

    /**
     * @var TaskPluginEventSubscriber
     */
    protected $subscriber;


    public function setUp()
    {
        $this->plugin = $this->getMock('Automaton\Task\TaskPlugin');
        $this->task = $this->getMock('Automaton\Task\TaskInterface');

        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runtimeEnvironment = $this->getMock('Automaton\RuntimeEnvironment', array(), array($this->input, $this->output, new ParameterBag(), new HelperSet()));
        $this->taskEvent = $this->getMock('Automaton\Console\Command\Event\TaskEvent', array(), array($this->task, $this->runtimeEnvironment));

        $this->subscriber = new TaskPluginEventSubscriber($this->plugin, $this->eventDispatcher );
    }

    /**
     * @test
     */
    public function runnable()
    {
        $this->taskEvent->expects($this->once())->method('getTask')->willReturn($this->createSimpleTask());
        $this->taskEvent->expects($this->once())->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->subscriber->onRun($this->taskEvent);
    }

    /**
     * @test
     */
    public function hasSubscribedEvents()
    {
        $this->assertInternalType('array', $this->subscriber->getSubscribedEvents());
    }

    /**
     * @test
     */
    public function runsTasksBefore()
    {
        $this->taskEvent->expects($this->once())->method('getTask')->willReturn($this->createSimpleTask(array(array($this->createSimpleTask()))));
        $this->taskEvent->expects($this->once())->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->subscriber->onRun($this->taskEvent);
    }

    /**
     * @test
     */
    public function runsTasksAfter()
    {
        $this->taskEvent->expects($this->once())->method('getTask')->willReturn($this->createSimpleTask(array(),array(array($this->createSimpleTask()))));
        $this->taskEvent->expects($this->once())->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->subscriber->onRun($this->taskEvent);
    }


    /**
     * @test
     */
    public function doesRunGroupTask()
    {
        $groupTask = $this->getMock('Automaton\Task\GroupTaskInterface', array(), array());
        $groupTask->expects($this->once())->method('getTasks')->willReturn(array($this->createSimpleTask(), $this->createSimpleTask()));
        $groupTask->expects($this->once())->method('getBefore')->willReturn(array(array($this->createSimpleTask())));
        $groupTask->expects($this->once())->method('getAfter')->willReturn(array(array($this->createSimpleTask())));

        $this->taskEvent->expects($this->once())->method('getTask')->willReturn($groupTask);
        $this->taskEvent->expects($this->once())->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->subscriber->onRun($this->taskEvent);
    }

    /**
     * @test
     */
    public function doesRunAlias()
    {
        $alias = $this->getMock('Automaton\Task\AliasInterface', array(), array());
        $alias->expects($this->once())->method('getOriginal')->willReturn($this->createSimpleTask());
        $alias->expects($this->once())->method('getBefore')->willReturn(array(array($this->createSimpleTask())));
        $alias->expects($this->once())->method('getAfter')->willReturn(array(array($this->createSimpleTask())));

        $this->taskEvent->expects($this->once())->method('getTask')->willReturn($alias);
        $this->taskEvent->expects($this->once())->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->subscriber->onRun($this->taskEvent);
    }

    private function createSimpleTask(array $before = array(), array $after = array(), $executes = true)
    {
        $task = $this->getMock('Automaton\Task\ExecutableTaskInterface');

        $task->expects($this->once())->method('getBefore')->willReturn($before);
        $task->expects($this->once())->method('getAfter')->willReturn($after);

        $task->expects($this->any())->method('showProgress')->willReturn(false);
        return $task;
    }
}