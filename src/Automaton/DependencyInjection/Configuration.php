<?php


namespace Automaton\DependencyInjection;


use Automaton\Config\Definition\Builder\SpecialNodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    protected $name = 'automaton';

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $builder->root($this->name, 'array', new SpecialNodeBuilder())
            ->useAttributeAsKey('name')->prototype('special')
            ->end();
        return $builder;
    }
}
