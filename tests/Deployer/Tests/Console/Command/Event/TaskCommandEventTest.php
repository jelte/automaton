<?php


namespace Deployer\Tests\Console\Command\Event;


use Deployer\Console\Command\Event\TaskCommandEvent;

class TaskCommandEventTest extends \PHPUnit_Framework_TestCase
{
    protected $command;

    /** @var TaskCommandEvent */
    protected $taskCommandEvent;

    public function setUp()
    {
        $this->command = $this->getMock('Deployer\Console\Command\RunTaskCommand', array(), array(), '', false);

        $this->taskCommandEvent = new TaskCommandEvent($this->command);
    }

    /**
     * @test
     */
    public function canAccessProperties()
    {
        $this->assertEquals($this->command, $this->taskCommandEvent->getCommand());
    }
} 