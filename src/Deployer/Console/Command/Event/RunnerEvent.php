<?php


namespace Deployer\Console\Command\Event;


use Deployer\Runner\Runner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;

class RunnerEvent extends Event
{
    /**
     * @var Runner
     */
    protected $runner;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(Runner $runner, InputInterface $input, OutputInterface $output)
    {
        $this->runner = $runner;
        $this->input = $input;
        $this->output = $output;
    }

    public function getRunner()
    {
        return $this->runner;
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