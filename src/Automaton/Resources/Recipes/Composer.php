<?php


namespace Automaton\Resources\Recipes;


use Automaton\Server\ServerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Automaton\Recipe\Annotation as Automaton;

/**
 * Class Composer
 * @package Automaton\Resources\Recipes
 */
class Composer
{
    /**
     * @param ServerInterface $server
     * @param OutputInterface $output
     *
     * @Automaton\Task
     * @Automaton\Before(task="composer:install")
     */
    public function download(ServerInterface $server, OutputInterface $output)
    {
        $command = "curl -s http://getcomposer.org/installer | php";
        if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $output->writeln("<info>DEBUG</info> [{$time}][@{$server->getName()}]$ $command");
        }
        $server->run($command);
    }

    /**
     * @param ServerInterface $server
     * @param OutputInterface $output
     *
     * @Automaton\Task
     */
    public function install(ServerInterface $server, OutputInterface $output)
    {
        $command = "php composer.phar install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress > composer.log";
        if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $output->writeln("<info>DEBUG</info> [{$time}][@{$server->getName()}]$ $command");
        }
        print $server->run($command);
    }
}
