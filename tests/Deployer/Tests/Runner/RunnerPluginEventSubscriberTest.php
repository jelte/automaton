<?php


namespace Deployer\Tests\Stage;


use Deployer\Plugin\AbstractPluginEventSubscriber;
use Deployer\Runner\RunnerPluginEventSubscriber;

class RunnerPluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $plugin;
    protected $runner;
    protected $input;
    protected $output;
    protected $runnerEvent;
    protected $servers;

    /**
     * @var AbstractPluginEventSubscriber
     */
    protected $subscriber;


    public function setUp()
    {
        $this->plugin = $this->getMock('Deployer\Runner\RunnerPlugin');
        $this->runner = $this->getMock('Deployer\Runner\Runner');
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runnerEvent = $this->getMock('Deployer\Console\Command\Event\RunnerEvent', array(), array($this->runner, $this->input, $this->output));
        $this->servers = array('server-1' => $this->getMock('Deployer\Server\ServerInterface'));

        $this->subscriber = new RunnerPluginEventSubscriber($this->plugin);
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
        $taskCommand = $this->getMock('Deployer\Console\Command\RunTaskCommand', array(), array(), '', false);
        $taskCommandEvent = $this->getMock('Deployer\Console\Command\Event\TaskCommandEvent', array(), array($taskCommand));

        $taskCommandEvent->expects($this->once())->method('getCommand')->willReturn($taskCommand);
        $taskCommand->expects($this->once())->method('addOption');

        $this->subscriber->onTaskCommandConfigure($taskCommandEvent);
    }

    /**
     * @test
     */
    public function replacesServersWithDryRunServersOnPreRun()
    {
        $this->runnerEvent->expects($this->exactly(2))->method('getRunner')->willReturn($this->runner);
        $this->runnerEvent->expects($this->once())->method('getInput')->willReturn($this->input);
        $this->runnerEvent->expects($this->exactly(count($this->servers)))->method('getOutput')->willReturn($this->output);
        $this->input->expects($this->once())->method('getOption')->with($this->equalTo('dry-run'))->willReturn(true);
        $this->runner->expects($this->once())->method('getServers')->willReturn($this->servers);
        $this->runner->expects($this->once())->method('setServers')->with($this->arrayHasKey('server-1'));

        $this->subscriber->onRunnerPreRun($this->runnerEvent);
    }

    /**
     * @test
     */
    public function resetsServersOnPostRun()
    {

        $this->runnerEvent->expects($this->never())->method('getRunner');
        $this->runnerEvent->expects($this->never())->method('getInput');

        $this->subscriber->onRunnerPostRun($this->runnerEvent);
    }
}