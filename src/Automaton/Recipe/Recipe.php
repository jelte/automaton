<?php


namespace Automaton\Recipe;


class Recipe
{
    protected $classname;

    public function __construct($classname)
    {
        $this->classname = $classname;
    }
    /*
    public function load()
    {
        //$reflection = new \ReflectionClass($this->classname);
    }*/
}
