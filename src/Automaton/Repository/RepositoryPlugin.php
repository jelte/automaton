<?php


namespace Automaton\Repository;

use Automaton\Plugin\AbstractPlugin;

class RepositoryPlugin extends AbstractPlugin
{
    protected $repository;

    protected $branch;

    protected $excludes;

    public function repository($repository)
    {
        $this->registerInstance('repository', $repository);
    }

    public function branch($branch)
    {
        $this->registerInstance('branch', $branch);
    }

    public function excludes(array $excludes)
    {
        $this->registerInstance('excludes', $excludes);
    }
}
