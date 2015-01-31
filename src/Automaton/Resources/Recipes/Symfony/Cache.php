<?php

namespace Automaton\Resources\Recipes\Symfony;

use Automaton\Recipe\Annotation as Automaton;
use Automaton\RuntimeEnvironment;
use Automaton\Server\ServerInterface;

class Cache
{

    /**
     * @Automaton\Task(description="Warm up Symfony cache")
     * @Automaton\After(task="composer:install")
     */
    public function warmup(RuntimeEnvironment $env, ServerInterface $server)
    {
        $stage = $env->get('stage')->getName();
        $server->run("php app/console --env={$stage} -v cache:warmup");
    }

}
