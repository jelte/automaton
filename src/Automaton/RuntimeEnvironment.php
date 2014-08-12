<?php


namespace Automaton;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuntimeEnvironment
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @var array
     */
    protected $values;

    public function set($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function get($name, $default = null)
    {
        return isset($this->values[$name]) ? $this->values[$name] : $default;
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