<?php


namespace Automaton\Recipe;


use Automaton\Server\ServerInterface;
use Automaton\Stage\StageInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Common {
    public static function debug(OutputInterface $output, ServerInterface $server, StageInterface $stage = null, $someParam)
    {
        $output->writeln('Debug:'.$server->getName());
    }
} 