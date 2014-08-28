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
        $server->run("curl -s http://getcomposer.org/installer | php");
    }

    /**
     * @param ServerInterface $server
     * @param OutputInterface $output
     *
     * @Automaton\Task
     * @Automaton\After(task="deploy", priority=20)
     */
    public function install(ServerInterface $server, OutputInterface $output)
    {
         print $server->run("php composer.phar install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress > composer.log");
    }
}
