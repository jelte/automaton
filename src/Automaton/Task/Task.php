<?php


namespace Automaton\Task;


class Task extends AbstractTask implements ExecutableTaskInterface
{
    protected $callable;

    public function __construct($name, $description = null, $callable, $public = true, $progress = true)
    {
        parent::__construct($name, $description, $public, $progress);
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
        if ( is_object($class) ) {
            return array($class, new \ReflectionMethod($class, $method));
        }
        return new \ReflectionMethod($class, $method);
    }
}
