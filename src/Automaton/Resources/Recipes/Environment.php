<?php


namespace Automaton\Resources\Recipes;


use Automaton\RuntimeEnvironment;
use Automaton\Server\ServerInterface;
use Automaton\Stage\StageInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Automaton\Recipe\Annotation as Automaton;



class Environment
{
    /**
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\Before(task="preDeploy")
     */
    public function init(ServerInterface $server)
    {
        $server->run("mkdir -p {$server->cwd('releases')}");
        $server->run("mkdir -p {$server->cwd('shared')}");
    }


    /**
     * @param RuntimeEnvironment $env
     * @param ServerInterface $server
     * @param OutputInterface $output
     *
     * @Automaton\Task
     * @Automaton\After(task="postDeploy")
     */
    public function createSymlink(RuntimeEnvironment $env, ServerInterface $server, OutputInterface $output)
    {
        $current = $server->cwd('release');
        $release = $server->cwd("releases/{$env->get('release')}");
        if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $output->writeln("<info>DEBUG</info> [{$time}][@{$server->getName()}]$ rm -f {$current} && ln -s {$release} {$current}");
        }
        $server->run("rm -f {$current} && ln -s {$release} {$current}");
    }

    /**
     * @param ServerInterface $server
     * @param StageInterface $stage
     *
     * @Automaton\Task
     * @Automaton\After(task="environment:createSymlink")
     */
    public function cleanup(ServerInterface $server, StageInterface $stage)
    {
        $releases = $server->run("ls -t {$server->cwd('releases')}");

        $keep = $stage->get('keep_releases', 3);

        while ($keep > 0) {
            array_shift($releases);
            --$keep;
        }

        foreach ($releases as $release) {
            $server->run("rm -rf releases/{$release}");
        }
    }
}
