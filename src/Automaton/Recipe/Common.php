<?php


namespace Automaton\Recipe;


use Automaton\Server\ServerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Common
{
    public static function debug(OutputInterface $output, ServerInterface $server)
    {
        $output->writeln('Debug:' . $server->getName());
    }
}
