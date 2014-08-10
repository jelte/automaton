<?php


namespace Deployer\Tests\Server;


use Deployer\Server\ServerPluginEventSubscriber;
use Deployer\Plugin\AbstractPluginEventSubscriber;

class ServerPluginEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $plugin;

    /**
     * @var AbstractPluginEventSubscriber
     */
    protected $subscriber;

    public function setUp()
    {
        $this->plugin = $this->getMock('Deployer\Server\ServerPlugin');

        $this->subscriber = new ServerPluginEventSubscriber($this->plugin);
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
    public function setsServersOnPreRun()
    {
        $runner = $this->getMock('Deployer\Runner\Runner');
        $input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $runnerEvent = $this->getMock('Deployer\Console\Command\Event\RunnerEvent', array('getRunner'), array($runner, $input, $output));

        $servers = array('server-1' => null, 'server-2' => null);
        $runnerEvent->expects($this->once())->method('getRunner')->willReturn($runner);
        $this->plugin->expects($this->once())->method('all')->willReturn($servers);
        $runner->expects($this->once())->method('setServers')->with($servers);
        $this->subscriber->onRunnerPreRun($runnerEvent);
    }

    /**
     * @test
     */
    public function setsSpecificServerOnPreRun()
    {
        $servers = array('server-1' => null, 'server-2' => null);
        $keys = array_keys($servers);
        $runner = $this->getMock('Deployer\Runner\Runner');
        $input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $runnerEvent = $this->getMock('Deployer\Console\Command\Event\RunnerEvent', array(), array($runner, $input, $output));
        $input->expects($this->once())->method('hasOption')->with($this->equalTo('server'))->will($this->returnValue(true));
        $input->expects($this->once())->method('getOption')->with($this->equalTo('server'))->willReturn($keys[0]);

        $runner->expects($this->exactly(2))->method('setServers')->withConsecutive(
            $this->equalTo($servers),
            $this->equalTo(array('server-1' => null))
        );

        $runnerEvent->expects($this->exactly(2))->method('getRunner')->willReturn($runner);
        $runnerEvent->expects($this->exactly(2))->method('getInput')->willReturn($input);

        $this->plugin->expects($this->once())->method('all')->willReturn($servers);

        $this->subscriber->onRunnerPreRun($runnerEvent);
    }

    /**
     * @test
     */
    public function resetsServersOnPostRun()
    {
        $servers = array('server-1' => null, 'server-2' => null);
         $runner = $this->getMock('Deployer\Runner\Runner');
        $input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $runnerEvent = $this->getMock('Deployer\Console\Command\Event\RunnerEvent', array(), array($runner, $input, $output));

        $runner->expects($this->once())->method('setServers')->with($this->equalTo($servers));

        $runnerEvent->expects($this->once())->method('getRunner')->willReturn($runner);

        $this->plugin->expects($this->once())->method('all')->willReturn($servers);

        $this->subscriber->onRunnerPostRun($runnerEvent);
    }
}