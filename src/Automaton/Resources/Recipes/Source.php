<?php


namespace Automaton\Resources\Recipes;


use Automaton\RuntimeEnvironment;
use Automaton\Server\ServerInterface;
use Automaton\System\FilesystemInterface;
use Automaton\System\SystemInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Automaton\Recipe\Annotation as Automaton;

class Source
{
    /**
     * @param RuntimeEnvironment $env
     * @param FilesystemInterface $filesystem
     *
     * @Automaton\Task
     * @Automaton\After(task="deploy")
     */
    public function prepare(RuntimeEnvironment $env, FilesystemInterface $filesystem)
    {
        $source = $env->get('repository.local_path');
        $release = date('YmdHis');
        $env->set('release', $release);
        $target = $source . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $release;
        $env->set('deploy.local_path', $target);
        if ($filesystem->exists($target)) {
            $filesystem->remove($target);
        }
        $filesystem->mirror($source, $target);
        $excludes = array_map(function ($value) use ($target) {
            return $target . DIRECTORY_SEPARATOR . $value;
        }, $env->get('excludes', array()));

        $filesystem->remove($excludes);
    }

    /**
     * @param RuntimeEnvironment $env
     * @param ServerInterface $server
     * @param SystemInterface $system
     * @param OutputInterface $output
     *
     * @Automaton\Task
     * @Automaton\After(task="source:prepare")
     */
    public function archive(RuntimeEnvironment $env, ServerInterface $server, SystemInterface $system, OutputInterface $output)
    {
        $release = $env->get('release');
        $path = realpath($env->get('repository.local_path') . DIRECTORY_SEPARATOR . '..');
        $archive = "{$release}.tar.gz";
        $system->run("cd {$path} && tar czf {$archive} {$release}");
        $env->set('release.archive', $archive);
        if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $output->writeln("<info>DEBUG</info> [{$time}][@{$server->getName()}]$ cd {$path} && tar czf {$archive} {$release}");
        }
    }

    /**
     * @param RuntimeEnvironment $env
     * @param ServerInterface $server
     *
     * @Automaton\Task
     * @Automaton\After(task="source:archive")
     */
    public function upload(RuntimeEnvironment $env, ServerInterface $server)
    {
        $archive = $env->get('release.archive');
        $target = "/tmp/{$archive}";
        $local = realpath($env->get('repository.local_path') . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $archive);
        $server->upload($local, $target);
    }

    /**
     * @param RuntimeEnvironment $env
     * @param ServerInterface $server
     * @param OutputInterface $output
     *
     * @Automaton\Task
     * @Automaton\After(task="source:upload")
     */
    public function extract(RuntimeEnvironment $env, ServerInterface $server, OutputInterface $output)
    {
        $archive = "/tmp/{$env->get('release.archive')}";
        $target = $server->cwd('releases');
        $release = $env->get('release');
        $finalTarget = $server->cwd("releases/{$release}");
        if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $time = date('H:i');
            $output->writeln("<info>DEBUG</info> [{$time}][@{$server->getName()}]$ cd {$target} && tar xzf {$archive} 1>archive.stdout.log 2>archive.stderr.log && rm {$archive} && cd {$finalTarget}");
        }
        $server->run("cd {$target} && tar xzf {$archive} 1>archive.stdout.log 2>archive.stderr.log && rm {$archive} && cd {$finalTarget}");
    }
}
