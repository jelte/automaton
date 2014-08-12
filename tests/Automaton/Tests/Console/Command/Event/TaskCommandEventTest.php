<?php


namespace Automaton\Tests\Console\Command\Event;


use Automaton\Console\Command\Event\TaskCommandEvent;

class TaskCommandEventTest extends \PHPUnit_Framework_TestCase
{
    protected $command;

    /** @var TaskCommandEvent */
    protected $taskCommandEvent;

    public function setUp()
    {
        $this->command = $this->getMock('Automaton\Console\Command\RunTaskCommand', array(), array(), '', false);

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