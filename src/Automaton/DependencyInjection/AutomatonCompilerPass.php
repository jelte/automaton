<?php


namespace Automaton\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AutomatonCompilerPass implements CompilerPassInterface
{
    protected $name = 'automaton';

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @param Configuration $configuration
     * @param Processor $processor
     */
    public function __construct(Configuration $configuration = null, Processor $processor = null)
    {
        $this->configuration = $configuration?$configuration:new Configuration($this->name);
        $this->processor = $processor?$processor:new Processor();
    }

    public function process(ContainerBuilder $container)
    {

        if (false === $container->hasDefinition($this->name)) {
            return;
        }

        $definition = $container->getDefinition($this->name);

        $config = $this->processor->processConfiguration($this->configuration, $container->getExtensionConfig($this->name));

        foreach ( $config as $method => $plugin ) {
            if ( $container->hasDefinition($this->name.'.plugin.'.$method) ) {
                $definition->addMethodCall('plugin', array(new Reference($this->name.'.plugin.'.$method)));
            }
            if ( is_array($plugin) ) {
                foreach ( $plugin as $name => $params ) {
                    if ( substr($name, 0, 1) !== '_') {
                        $params = is_array($params) ? $params : array($params);
                        array_unshift($params, $name);
                        $definition->addMethodCall($method, $params);
                    }
                }
            } else {
                $definition->addMethodCall($method, array($plugin));
            }
        }
    }
} 