<?php


namespace Deployer\Recipe;


use Deployer\Server\ServerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Common {
    public static function debug(OutputInterface $output, ServerInterface $server)
    {
        $output->writeln('Debug:'.$server->getName());
    }
} 