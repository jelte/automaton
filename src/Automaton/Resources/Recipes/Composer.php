<?php


namespace Automaton\Resources\Recipes;


use Automaton\Server\ServerInterface;
use Automaton\Recipe\Annotation as Automaton;

/**
 * Class Composer
 * @package Automaton\Resources\Recipes
 */
class Composer
{
    /**
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\Before(task="composer:install")
     */
    public function download(ServerInterface $server)
    {
        $server->run("curl -s http://getcomposer.org/installer | php");
    }

    /**
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\After(task="deploy", priority=20)
     */
    public function install(ServerInterface $server)
    {
         $server->run("php composer.phar install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress > composer.log");
    }

    /**
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\Before(task="composer:install")
     */
    public function copyPreviousVendors(ServerInterface $server)
    {
        $server->run("if test -d {$server->cwd('release/vendor')}; then cp -R {$server->cwd('release/vendor')} . ; fi");
    }

    /**
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\After(task="composer:install")
     */
    public function cleanup(ServerInterface $server)
    {
        $server->run("rm -rf composer.*");
    }
}
