<?php


namespace Deployer\Tests\Console\Command\Event;


use Deployer\Console\Command\Event\TaskEvent;

class TaskEventTest extends \PHPUnit_Framework_TestCase
{
    protected $task, $runtimeEnvironment;

    /** @var TaskEvent */
    protected $taskEvent;

    public function setUp()
    {
        $this->task = $this->getMock('Deployer\Task\TaskInterface');
        $this->runtimeEnvironment = $this->getMock('Deployer\RuntimeEnvironment', array(), array(), '', false);

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