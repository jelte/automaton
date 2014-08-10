<?php


namespace Deployer\Config\Definition\Builder;


use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class SpecialNodeBuilder extends NodeBuilder
{
    public function __construct()
    {
        parent::__construct();
        $this->nodeMapping = array_merge($this->nodeMapping, array(
            'special'    => __NAMESPACE__.'\\SpecialNodeDefinition',
        ));
    }
}
