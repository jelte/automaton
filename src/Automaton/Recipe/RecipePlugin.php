<?php


namespace Automaton\Recipe;


use Automaton\Plugin\AbstractPlugin;

class RecipePlugin extends AbstractPlugin
{
    public function recipe($name, $classname)
    {
        $this->registerInstance($name, new Recipe($classname));
    }
}
