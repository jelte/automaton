<?php


namespace Automaton\Server;

use Automaton\Plugin\AbstractPlugin;
use Automaton\Server\Ssh\PhpSecLibConnection;
use Automaton\Server\Ssh\SshConnection;
use Automaton\Ssh2\Session;
use Automaton\Ssh2\Tunnel;

class ServerPlugin extends AbstractPlugin
{
    /**
     * @param $name
     * @param $config
     * @return ServerInterface
     */
    public function server($name, array $config)
    {
        $hops = array_key_exists('gateways', $config) ? $config['gateways'] : array();
        if ( isset($config['type']) && $config['type'] == 'ssh2-automaton' ) {
            $session = new Session($config['host'],
                array_key_exists('arguments', $config) ? $config['arguments'] : array(),
                array_key_exists('options', $config) ? $config['options'] : array(),
                new Tunnel($hops)
            );
            $connection = new SshConnection($session);
            if (isset($config['auth']) && method_exists($session, $config['auth'])) {
                $method = new \ReflectionMethod($session, $config['auth']);
                $parameters = array();
                foreach ($method->getParameters() as $parameter) {
                    $parameters[$parameter->getName()] = array_key_exists($parameter->getName(), $config) ? $config[$parameter->getName()] : null;
                }
                $method->invokeArgs($session, $parameters);
            }
        } else {
            $session = new \Net_SFTP($config['host'], isset($config['options']['PORT'])?$config['options']['PORT']:22);
            if ($config['auth'] == 'publicKeyFile') {
                $key = new \Crypt_RSA();
                $key->setPassword(isset($config['passphrase'])?$config['passphrase']:null);
                $key->loadKey(file_get_contents(isset($config['privateKeyFile'])?$config['privateKeyFile']:getenv("HOME").'/.ssh/id_rsa'));
                $session->login($config['username'], $key);
            } elseif ($config['auth'] == 'password') {
                $session->login($config['username'], $config['password']);
            } elseif ($config['auth'] == 'pem') {
                $key = new \Crypt_RSA();
                $key->loadKey(file_get_contents($config['pemFile']));
                $session->login($config['username'], $key);
            } else {
                $session->login($config['username']);
            }
            $connection = new PhpSecLibConnection($session);
        }
        return $this->registerInstance($name, new SshServer($name, $connection, array_key_exists('path', $config) ? $config['path'] : null));
    }
}
