<?php


namespace Automaton\Plugin;


class AbstractPlugin implements PluginInterface
{
    /**
     * @var array
     */
    private $instances = array();

    /**
     * @internal
     * @return array
     */
    public function all()
    {
        return $this->instances;
    }

    /**
     * @internal
     */
    public function get($name)
    {
        if (null === $name) {
            throw new \InvalidArgumentException('Name can not be null');
        }
        if (!isset($this->instances[$name])) {
            throw new \InvalidArgumentException('No instance found for ' . $name);
        }
        return $this->instances[$name];
    }

    /**
     * @internal
     * @param $name
     * @param $instance
     * @return mixed
     */
    public function registerInstance($name, $instance)
    {
        $this->instances[$name] = $instance;
        return $instance;
    }
}
