<?php


namespace Automaton\Resources\Recipes;

use Automaton\Recipe\Annotation as Automaton;

class Common
{

    /**
     * @Automaton\Task(description="Deploy your application")
     * @Automaton\Alias(name="deploy")
     */
    public function deploy()
    {

    }

    /**
     * @Automaton\Task(description="Rollback your application to a previous version")
     * @Automaton\Alias(name="rollback")
     */
    public function rollback()
    {

    }
}
