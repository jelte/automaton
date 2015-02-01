<?php


namespace Automaton;


use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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

    protected $parameterBag;

    public function __construct(InputInterface $input, OutputInterface $output, ParameterBagInterface $parameterBag, HelperSet $helperSet = null)
    {
        $this->set('input', $input);
        $this->set('output', $output);
        $this->set('runtimeEnvironment', $this);
        $this->set('env', $this);
        $this->set('helperSet', $helperSet);
        $this->parameterBag = $parameterBag;

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
        return isset($this->values[$name]) ? $this->values[$name] : ($this->parameterBag->has($name) ? $this->parameterBag->get($name) : $default);
    }

    /**
     * @return InputInterface|null
     */
    public function getInput()
    {
        return $this->get('input', null);
    }

    public function getOutput()
    {
        return $this->get('output', null);
    }
}
