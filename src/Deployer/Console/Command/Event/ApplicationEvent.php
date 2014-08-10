<?php


namespace Deployer\Console\Command\Event;


use Deployer\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;

class ApplicationEvent extends Event
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(Application $application, InputInterface $input, OutputInterface $output)
    {
        $this->application = $application;
        $this->input = $input;
        $this->output = $output;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getOutput()
    {
        return $this->output;
    }
} 