<?php


namespace Automaton\Stage;

use Automaton\Plugin\AbstractPlugin;

class StagePlugin extends AbstractPlugin
{
    protected $defaultInstance;


    public function stage($name, array $servers, array $options = array(), $default = false)
    {
        if ($default) {
            $this->defaultInstance = $name;
        }
        return $this->registerInstance($name, new Stage($name, $servers, $options));
    }

    /**
     * @internal
     * @return mixed
     */
    public function getDefaultInstance()
    {
        return $this->defaultInstance;
    }
}
