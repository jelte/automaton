<?php


namespace Automaton\Console\Command\Event;


use Automaton\Console\Command\RunTaskCommand;
use Symfony\Component\EventDispatcher\Event;

class TaskCommandEvent extends Event {

    /**
     * @var RunTaskCommand
     */
    protected $command;

    public function __construct(RunTaskCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @return RunTaskCommand
     */
    public function getCommand()
    {
        return $this->command;
    }
}
