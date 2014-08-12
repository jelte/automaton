<?php


namespace Automaton\Tests\Console\Command;


use Automaton\Console\Command\RunTaskCommand;

class RunTaskCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $task;

    protected $eventDispatcher;

    protected $input;

    protected $output;

    /**
     * @var RunTaskCommand
     */
    protected $command;

    public function setUp()
    {
        $this->task = $this->getMock('Automaton\Task\TaskInterface');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->task->expects($this->once())->method('getName')->willReturn('debug');

        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->command = new RunTaskCommand($this->task, $this->eventDispatcher);
    }

    /**
     * @test
     */
    public function copiesNameFromTask()
    {
        $this->assertEquals('debug', $this->command->getName());
    }

    /**
     * @test
     */
    public function canBeExecuted()
    {
        $this->eventDispatcher->expects($this->exactly(3))->method('dispatch')->withConsecutive(
            array($this->equalTo('automaton.task.pre_run')),
            array($this->equalTo('automaton.task.run')),
            array($this->equalTo('automaton.task.post_run'))
        );

        $this->command->run($this->input, $this->output);
    }
} 