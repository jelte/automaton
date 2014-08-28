<?php


namespace Automaton\Resources\Recipes;

use Automaton\Recipe\Annotation as Automaton;
use Symfony\Component\Console\Output\OutputInterface;

class Common
{

    /**
     * @Automaton\Task(description="Deploy your application")
     * @Automaton\Alias(name="deploy")
     */
    public function deploy(OutputInterface $output)
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
