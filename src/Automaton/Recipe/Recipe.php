<?php


namespace Automaton\Recipe;


use Automaton\Task\Task;

class Recipe
{
    protected $classname;

    public function __construct($classname)
    {
        $this->classname = $classname;
    }

    public function load()
    {
        $reflection = new \ReflectionClass($this->classname);
        $recipe = $reflection->newInstance();
        $prefix = str_replace("\\",":",strtolower(substr($reflection->getName(),strpos($reflection->getName(), 'Recipes')+8)));
        $tasks = array();
        foreach ( $reflection->getMethods() as $method ) {
            $tasks[] = new Task($prefix.':'.$method->getName(), '', array($recipe, $method->getName()));
        }
        return $tasks;
    }
}
