<?php


namespace Deployer\Plugin;


class PluginManager extends AbstractPlugin
{
    /**
     * @param array $plugins
     */
    public function __construct(array $plugins = array())
    {
        $this->plugin($this);
        foreach ($plugins as $plugin) {
            $this->plugin($plugin);
        }
    }

    public function plugin(PluginInterface $plugin)
    {
        $reflection = new \ReflectionClass($plugin);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ( !$method->isConstructor() && !$method->isDestructor() && !$method->isDeprecated() ) {
                if (!$method->getDocComment() || !preg_match('/@internal/s', $method->getDocComment(), $annotions)) {
                    $this->registerInstance($method->getName(), $plugin);
                }
            }
        }
    }
}