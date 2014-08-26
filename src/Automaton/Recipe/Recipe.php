<?php


namespace Automaton\Recipe;

use Automaton\Task\Alias;
use Automaton\Task\Task;


class Recipe
{
    protected $classname;

    protected $reader;

    public function __construct($classname)
    {
        $this->classname = $classname;
        $this->reader = new AnnotationReader();
    }

    public function tasks()
    {
        $reflection = new \ReflectionClass($this->classname);
        $recipe = $reflection->newInstance();
        $prefix = str_replace("\\",":",strtolower(substr($reflection->getName(),strpos($reflection->getName(), 'Recipes')+8)));
        $tasks = array();
        foreach ( $reflection->getMethods() as $method ) {
            if ( $annotation = $this->reader->getMethodAnnotation($method, 'Automaton\Recipe\Annotation\Task') ) {
                $task = new Task($prefix . ':' . $method->getName(), $annotation->description, array($recipe, $method->getName()), $annotation->public);
                $tasks[] = $task;
                if ($alias = $this->reader->getMethodAnnotation($method, 'Automaton\Recipe\Annotation\Alias')) {
                    $tasks[] = new Alias($alias->name, $task, $alias->public);
                }
            }
        }
        return $tasks;
    }

    public function befores()
    {
        $reflection = new \ReflectionClass($this->classname);
        $prefix = str_replace("\\",":",strtolower(substr($reflection->getName(),strpos($reflection->getName(), 'Recipes')+8)));
        $tasks = array();
        foreach ( $reflection->getMethods() as $method ) {
            foreach ($this->reader->getMethodAnnotations($method, 'Automaton\Recipe\Annotation\Before')  as $before) {
                $tasks[] = array($prefix.':'.$method->getShortName(), $before->task);
            }
        }
        return $tasks;
    }

    public function afters()
    {
        $reflection = new \ReflectionClass($this->classname);
        $prefix = str_replace("\\",":",strtolower(substr($reflection->getName(),strpos($reflection->getName(), 'Recipes')+8)));
        $tasks = array();
        foreach ( $reflection->getMethods() as $method ) {
            foreach ($this->reader->getMethodAnnotations($method, 'Automaton\Recipe\Annotation\After')  as $after) {
                $tasks[] = array($prefix.':'.$method->getShortName(), $after->task);
            }
        }
        return $tasks;
    }
}
