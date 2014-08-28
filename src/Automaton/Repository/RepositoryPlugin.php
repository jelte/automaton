<?php


namespace Automaton\Repository;

use Automaton\Plugin\PluginInterface;

class RepositoryPlugin implements PluginInterface
{
    protected $repository;

    protected $branch;

    protected $excludes;

    public function repository($repository)
    {
        $this->repository = $repository;
    }

    public function branch($branch)
    {
        $this->branch = $branch;
    }

    public function excludes($excludes)
    {
        if ( !is_array($excludes)) {
            throw new \Exception();
        }
        $this->excludes = $excludes;
    }

    /** @internal */
    public function getExcludes()
    {
        return $this->excludes;
    }

    /** @internal */
    public function getRepository()
    {
        return $this->repository;
    }

    /** @internal */
    public function getBranch()
    {
        return $this->branch;
    }

    public function get($name)
    {
        throw new \DomainException('get() not supported for this plugin');
    }

    public function all()
    {
        throw new \DomainException('all() not supported for this plugin');
    }
}
