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
        $this->set('input', $input);
        $this->set('output', $output);
        $this->set('runtimeEnvironment', $this);
        $this->set('env', $this);
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
        return $this->get('input', null);
    }

    public function getOutput()
    {
        return $this->get('output', null);
    }
}