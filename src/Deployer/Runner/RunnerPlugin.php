<?php


namespace Deployer\Runner;


use Deployer\Plugin\AbstractPlugin;

class RunnerPlugin extends AbstractPlugin
{
    /**
     * @internal
     * @param $name
     * @return mixed
     */
    public function get($name = null)
    {
        return parent::get('runner');
    }

    public function runner()
    {
        return $this->registerInstance('runner', new Runner());
    }
}