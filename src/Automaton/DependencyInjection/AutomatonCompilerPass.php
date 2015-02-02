<?php


namespace Automaton\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
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
        $this->configuration = null === $configuration ? new Configuration($this->name) : $configuration;
        $this->processor = $processor ? $processor : new Processor();
    }

    public function process(ContainerBuilder $container)
    {

        if (false === $container->hasDefinition($this->name)) {
            return;
        }

        $definition = $container->getDefinition($this->name);

        $config = $this->processor->processConfiguration($this->configuration, $container->getExtensionConfig($this->name));

        $reflectionClass = null;
        foreach ($config as $method => $plugin) {
            if ($container->hasDefinition($this->name . '.plugin.' . $method)) {
                $definition->addMethodCall('plugin', array(new Reference($this->name . '.plugin.' . $method)));

                if (is_array($plugin)) {
                    $this->processPlugin($method, $plugin, $definition, $container->getDefinition($this->name . '.plugin.' . $method));
                } else {
                    $definition->addMethodCall($method, array($plugin));
                }
            } else {
                $definition->addMethodCall($method, array($plugin));
            }
        }
    }

    protected function processPlugin($method, $plugin, Definition $definition, Definition $pluginDefinition)
    {
        $reflectionClass = new \ReflectionClass($pluginDefinition->getClass());
        $reflectionMethod = $reflectionClass->getMethod($method);
        /** @var \ReflectionParameter[] $parameters */
        $parameters = $reflectionMethod->getParameters();
        if (isset($parameters[0]) && $parameters[0]->isArray()) {
            $definition->addMethodCall($method, array(array_unique($plugin)));
        } else {
            foreach ($plugin as $name => $params) {
                if (substr($name, 0, 1) !== '_') {
                    if (count($parameters) == 2 && isset($parameters[1]) && $parameters[1]->isArray()) {
                        $params = array($name, $params);
                    } else {
                        $params = is_array($params) ? $params : array($params);
                        array_unshift($params, $name);
                    }
                    $definition->addMethodCall($method, $params);
                }
            }
        }
    }
}
