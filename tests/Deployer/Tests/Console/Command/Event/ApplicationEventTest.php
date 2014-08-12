<?php


namespace Deployer\Tests\Console\Command\Event;


use Deployer\Console\Command\Event\ApplicationEvent;

class ApplicationEventTest extends \PHPUnit_Framework_TestCase
{
    protected $application, $input, $output;

    /** @var ApplicationEvent */
    protected $applicationEvent;

    public function setUp()
    {
        $this->application = $this->getMock('Deployer\Console\Application', array(), array(), '', false);
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->applicationEvent = new ApplicationEvent($this->application, $this->input, $this->output);
    }

    /**
     * @test
     */
    public function canAccessProperties()
    {
        $this->assertEquals($this->application, $this->applicationEvent->getApplication());
        $this->assertEquals($this->input, $this->applicationEvent->getInput());
        $this->assertEquals($this->output, $this->applicationEvent->getOutput());
    }
} 