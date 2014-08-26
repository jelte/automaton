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
     * @Automaton\Task
     * @Automaton\Before(task="common:deploy")
     */
    public function preDeploy()
    {

    }

    /**
     * @Automaton\Task
     * @Automaton\After(task="common:postDeploy", priority=999)
     */
    public function postDeploy()
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
