<?php


namespace Automaton\Role;

use Automaton\Plugin\AbstractPlugin;

class RolePlugin extends AbstractPlugin
{
    /**
     * @param $name
     * @param $servers
     * @return RoleInterface
     */
    public function role($name, array $servers)
    {
         return $this->registerInstance($name, new Role($name, $servers));
    }
}
