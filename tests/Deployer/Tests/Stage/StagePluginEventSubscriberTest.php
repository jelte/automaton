<?php


namespace Deployer\Tests\Stage;

use Deployer\Stage\StagePluginEventSubscriber;

class StagePluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $plugin, $task, $runtimeEnvironment, $input, $output, $taskEvent,$servers;

    /**
     * @var StagePluginEventSubscriber
     */
    protected $subscriber;


    public function setUp()
    {
        $this->plugin = $this->getMock('Deployer\Stage\StagePlugin');
        $this->task = $this->getMock('Deployer\Task\TaskInterface');
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runtimeEnvironment = $this->getMock('Deployer\RuntimeEnvironment', array(), array($this->input, $this->output));
        $this->taskEvent = $this->getMock('Deployer\Console\Command\Event\TaskEvent', array(), array($this->task, $this->runtimeEnvironment));
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

        $this->subscriber->configureTaskCommand($taskCommandEvent);
    }

    /**
     * @test
     */
    public function preTaskRunAddsStageAndServersToEnvironment()
    {
        $stage = $this->getMock('Deployer\Stage\Stage', array(), array('develop', array('server-2')));

        $this->runtimeEnvironment->expects($this->once())->method('getInput')->willReturn($this->input);
        $this->taskEvent->expects($this->once(2))->method('getRuntimeEnvironment')->willReturn($this->runtimeEnvironment);
        $this->input->expects($this->once())->method('hasArgument')->with($this->equalTo('stage'))->will($this->returnValue(true));
        $this->input->expects($this->once())->method('getArgument')->with($this->equalTo('stage'))->willReturn('develop');
        $this->plugin->expects($this->once())->method('get')->with($this->equalTo('develop'))->willReturn($stage);
        $stage->expects($this->once())->method('getServers')->willReturn(array('server-2'));
        $this->runtimeEnvironment->expects($this->once())->method('get')->with($this->equalTo('servers'))->willReturn($this->servers);
        $this->runtimeEnvironment->expects($this->exactly(2))->method('set')->withConsecutive(array('stage', $stage), array('servers', array('server-2' => null)));

        $this->subscriber->preTaskRun($this->taskEvent);
    }
}