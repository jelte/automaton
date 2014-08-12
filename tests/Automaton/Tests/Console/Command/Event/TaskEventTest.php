<?php


namespace Automaton\Tests\Console\Command\Event;


use Automaton\Console\Command\Event\TaskEvent;

class TaskEventTest extends \PHPUnit_Framework_TestCase
{
    protected $task, $runtimeEnvironment;

    /** @var TaskEvent */
    protected $taskEvent;

    public function setUp()
    {
        $this->task = $this->getMock('Automaton\Task\TaskInterface');
        $this->runtimeEnvironment = $this->getMock('Automaton\RuntimeEnvironment', array(), array(), '', false);

        $this->taskEvent = new TaskEvent($this->task, $this->runtimeEnvironment);
    }

    /**
     * @test
     */
    public function canAccessProperties()
    {
        $this->assertEquals($this->task, $this->taskEvent->getTask());
        $this->assertEquals($this->runtimeEnvironment, $this->taskEvent->getRuntimeEnvironment());
    }
} 