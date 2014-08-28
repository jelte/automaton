<?php


namespace Automaton\Recipe;


use Automaton\Plugin\AbstractPlugin;

class RecipePlugin extends AbstractPlugin
{
    public function recipe($recipes)
    {
        if ( !is_array($recipes) ) {
            $recipes = array($recipes);
        }
        foreach ( $recipes as $classname ) {
            $this->registerInstance($classname, new Recipe($classname));
        }
    }
}
