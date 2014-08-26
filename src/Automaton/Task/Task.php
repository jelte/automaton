<?php


namespace Automaton\Task;


class Task extends AbstractTask implements ExecutableTaskInterface
{
    protected $callable;

    public function __construct($name, $description = null, $callable)
    {
        parent::__construct($name, $description);
        $this->callable = $callable;
    }

    /**
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public function getCallable()
    {
        $callable = $this->callable;
        if ((is_string($callable) && (!strpos($callable, '::') && function_exists($callable))) || $callable instanceof \Closure ) {
            return new \ReflectionFunction($callable);
        }
        if ( is_string($callable) ) {
            $callable = explode("::", $callable);
        }
        list($class, $method) = $callable;
        return new \ReflectionMethod($class, $method);
    }
}
