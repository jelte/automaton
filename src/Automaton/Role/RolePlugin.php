<?php


namespace Automaton\Role;

use Automaton\Plugin\AbstractPlugin;
use Automaton\Server\Ssh\PhpSecLibConnection;
use Automaton\Server\Ssh\SshConnection;
use Automaton\Ssh2\Session;
use Automaton\Ssh2\Tunnel;

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
