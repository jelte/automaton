<?php


namespace Automaton\Server;

use Automaton\Exception\InvalidArgumentException;
use Automaton\Plugin\AbstractPlugin;
use Automaton\Server\Ssh\PhpSecLib;
use Automaton\Server\Ssh\SshConnection;
use Automaton\Ssh2\Session;
use Automaton\Ssh2\Tunnel;
use Automaton\Utils\Uri;

class ServerPlugin extends AbstractPlugin
{
    /**
     * @param $name
     * @param $config
     * @return ServerInterface
     */
    public function server($name, array $config)
    {
        $hops = array_key_exists('gateways', $config)?$config['gateways']:array();
        $session = new Session($config['host'],
            array_key_exists('arguments', $config)?$config['arguments']:array(),
            array_key_exists('options', $config)?$config['options']:array(),
            new Tunnel($hops)
        );
        $server = new SshServer($name, new SshConnection($session), array_key_exists('path',$config)?$config['path']:null);
        if ( isset($config['auth']) && method_exists($session, $config['auth']) ) {
            $method = new \ReflectionMethod($session, $config['auth']);
            $parameters = array();
            foreach ( $method->getParameters() as $parameter ) {
                $parameters[$parameter->getName()] = array_key_exists($parameter->getName(), $config)?$config[$parameter->getName()]:null;
            }
            $method->invoke($session, $parameters);
        }
        return $this->registerInstance($name, $server);
    }
}