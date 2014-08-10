<?php


namespace Deployer;


use Deployer\Plugin\PluginManager;

class Deployer
{
    /**
     * @var PluginManager
     */
    protected $plugins;

    public function __construct(PluginManager $pluginManager)
    {
        $this->plugins = $pluginManager;
    }

    public function get($plugin, $name = null)
    {
        return $this->plugins->get($plugin)->get($name);
    }

    public function all($plugin)
    {
        return $this->plugins->get($plugin)->all();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->plugins->get($name), $name), $arguments);
    }
} 