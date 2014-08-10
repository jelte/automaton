<?php


namespace Deployer\Tests\Stage;


use Deployer\Plugin\AbstractPluginEventSubscriber;
use Deployer\Stage\StagePluginEventSubscriber;

class StagePluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
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
        $this->plugin = $this->getMock('Deployer\Stage\StagePlugin');
        $this->runner = $this->getMock('Deployer\Runner\Runner');
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runnerEvent = $this->getMock('Deployer\Console\Command\Event\RunnerEvent', array(), array($this->runner, $this->input, $this->output));
        $this->servers = array('server-1' => null, 'server-2' => null);

        $this->subscriber = new StagePluginEventSubscriber($this->plugin);
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
        $taskCommand->expects($this->once())->method('addArgument');

        $this->subscriber->onTaskCommandConfigure($taskCommandEvent);
    }

    /**
     * @test
     */
    public function setsStageServersOnPreRun()
    {
        $stage = $this->getMock('Deployer\Stage\Stage', array(), array('develop', array('server-2')));

        $this->runnerEvent->expects($this->exactly(2))->method('getRunner')->willReturn($this->runner);
        $this->runnerEvent->expects($this->exactly(2))->method('getInput')->willReturn($this->input);
        $this->input->expects($this->once())->method('hasArgument')->with($this->equalTo('stage'))->will($this->returnValue(true));
        $this->input->expects($this->once())->method('getArgument')->with($this->equalTo('stage'))->willReturn('develop');
        $this->plugin->expects($this->once())->method('get')->with($this->equalTo('develop'))->willReturn($stage);
        $stage->expects($this->once())->method('getServers')->willReturn(array('server-2'));
        $this->runner->expects($this->once())->method('getServers')->willReturn($this->servers);
        $this->runner->expects($this->once())->method('setServers')->with(array('server-2' => null));

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