<?php


namespace Automaton\Tests\Console\Command;


use Automaton\Console\Command\RunTaskCommand;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class RunTaskCommandTest extends ProphecyTestCase
{
    protected $task;

    protected $eventDispatcher;

    protected $input;

    protected $output;

    protected $helperSet;

    /**
     * @var RunTaskCommand
     */
    protected $command;

    public function setUp()
    {
        parent::setUp();
        $this->task = $this->prophesize('Automaton\Task\TaskInterface');
        $this->eventDispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->task->getName()->willReturn('debug');

        $this->input = $this->prophesize('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->prophesize('Symfony\Component\Console\Output\OutputInterface');
        $this->helperSet = $this->prophesize('Symfony\Component\Console\Helper\HelperSet');

        $this->command = new RunTaskCommand($this->task->reveal(), $this->eventDispatcher->reveal(), new ParameterBag());
        $this->command->setHelperSet($this->helperSet->reveal());
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
        $this->eventDispatcher->dispatch('automaton.task.pre_run', Argument::type('Automaton\Console\Command\Event\TaskEvent'))->shouldBeCalled();
        $this->eventDispatcher->dispatch('automaton.task.run', Argument::type('Automaton\Console\Command\Event\TaskEvent'))->shouldBeCalled();
        $this->eventDispatcher->dispatch('automaton.task.post_run', Argument::type('Automaton\Console\Command\Event\TaskEvent'))->shouldBeCalled();

        $this->command->run($this->input->reveal(), $this->output->reveal());

        $this->assertTrue(true);
    }
} 