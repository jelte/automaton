<?php


namespace Automaton\Tests\Console;


use Automaton\Console\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $input;

    protected $output;

    /**
     * @var Application
     */
    protected $application;

    protected $configurationLoader;

    protected $container;

    protected $eventDispatcher;

    public function setUp()
    {
        $this->configurationLoader = $this->getMock('Automaton\Config\ConfigurationLoader');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->application = new Application('Automaton', 'TEST', $this->configurationLoader);
    }

    /**
     * @test
     */
    public function canRun()
    {
        $this->assertNull($this->application->getContainer());

        $this->configurationLoader->expects($this->once())->method('load')->willReturn($this->container);

        $this->container->expects($this->exactly(2))->method('get')->with('event_dispatcher')->willReturn($this->eventDispatcher);

        $this->eventDispatcher->expects($this->atLeastOnce())->method('dispatch');

        $this->application->doRun($this->input, $this->output);
    }

    /**
     * @test
     */
    public function canBeProfiled()
    {

        $this->input->expects($this->exactly(3))->method('hasParameterOption')->withConsecutive(
            array($this->equalTo(array('--profile'))),
            array($this->equalTo(array('--version', '-V'))),
            array($this->equalTo(array('--help', '-h')))
        )->will(
            $this->onConsecutiveCalls(true, false, false)
        );

        $this->output->expects($this->once())->method('writeln');

        $this->configurationLoader->expects($this->once())->method('load')->willReturn($this->container);

        $this->container->expects($this->exactly(2))->method('get')->with('event_dispatcher')->willReturn($this->eventDispatcher);


        $this->eventDispatcher->expects($this->atLeastOnce())->method('dispatch');

        $this->application->doRun($this->input, $this->output);
    }
}