<?php


namespace Deployer\Tests\Console\Command;


use Deployer\Console\Command\RunTaskCommand;

class RunTaskCommandTest extends \PHPUnit_Framework_TestCase
{

    protected $runner;

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
        $this->runner = $this->getMock('Deployer\Runner\Runner');
        $this->task = $this->getMock('Deployer\Task\TaskInterface');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->task->expects($this->once())->method('getName')->willReturn('debug');

        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->command = new RunTaskCommand($this->runner, $this->task, $this->eventDispatcher);
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
        $this->runner->expects($this->once())->method('setUp');
        $this->runner->expects($this->once())->method('run')->with($this->task);

        $this->eventDispatcher->expects($this->exactly(2))->method('dispatch')->withConsecutive(
            array($this->equalTo('deployer.runner.pre_run')),
            array($this->equalTo('deployer.runner.post_run'))
        );

        $this->command->run($this->input, $this->output);
    }
} 