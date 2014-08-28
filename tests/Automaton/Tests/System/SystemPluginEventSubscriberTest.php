<?php


namespace Automaton\Tests\Stage;

use Automaton\Stage\StagePluginEventSubscriber;
use Automaton\System\SystemPluginEventSubscriber;

class SystemPluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $system, $task, $runtimeEnvironment, $input, $output, $taskEvent,$servers;

    /**
     * @var StagePluginEventSubscriber
     */
    protected $subscriber;


    public function setUp()
    {
        $this->system = $this->getMock('Automaton\System\System', array(), array(), '', false);
        $this->task = $this->getMock('Automaton\Task\TaskInterface');
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runtimeEnvironment = $this->getMock('Automaton\RuntimeEnvironment', array(), array($this->input, $this->output));
        $this->taskEvent = $this->getMock('Automaton\Console\Command\Event\TaskEvent', array(), array($this->task, $this->runtimeEnvironment));

        $this->subscriber = new SystemPluginEventSubscriber($this->system);
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
    public function addsServerParameter()
    {
        $taskCommand = $this->getMock('Automaton\Console\Command\RunTaskCommand', array(), array(), '', false);
        $taskCommandEvent = $this->getMock('Automaton\Console\Command\Event\TaskCommandEvent', array(), array($taskCommand));

        $taskCommandEvent->expects($this->once())->method('getCommand')->willReturn($taskCommand);
        $taskCommand->expects($this->once())->method('addOption');

        $this->subscriber->configureTaskCommand($taskCommandEvent);
    }

    /**
     * @test
     */
    public function preTaskRunAddsSystemAndFilesystemToEnvironment()
    {
        $this->runtimeEnvironment->expects($this->once())->method('getInput')->willReturn($this->input);
        $this->runtimeEnvironment->expects($this->exactly(2))->method('getOutput')->willReturn($this->output);
        $this->taskEvent->expects($this->once(2))->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->input->expects($this->once())->method('getOption')->with($this->equalTo('dry-run'))->will($this->returnValue(true));
        $this->runtimeEnvironment->expects($this->exactly(2))->method('set')->withConsecutive(array('system'), array('filesystem'));

        $this->subscriber->preTaskRun($this->taskEvent);
    }
}