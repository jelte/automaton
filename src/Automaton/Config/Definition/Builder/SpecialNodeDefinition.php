<?php


namespace Automaton\Config\Definition\Builder;


use Automaton\Config\Definition\SpecialNode;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class SpecialNodeDefinition extends NodeDefinition
{

    /**
     * Instantiate and configure the node according to this definition
     *
     * @return NodeInterface $node The node instance
     *
     * @throws InvalidDefinitionException When the definition is invalid
     */
    protected function createNode()
    {
        return new SpecialNode($this->name);
    }
}
