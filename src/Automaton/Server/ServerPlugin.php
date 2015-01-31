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
        return $this->registerInstance($name, new SshServer($name, new PhpSecLibConnection($this->createSession($config)), array_key_exists('path', $config) ? $config['path'] : null));
    }

    protected function createSession(array $config)
    {
        if (!isset($config['username'])) $config['username'] = null;
        $session = new \Net_SFTP($config['host'], isset($config['options']['port']) ? $config['options']['port'] : 22);
        $session->login($config['username'], $this->createKey($config));
        return $session;
    }

    protected function createKey(array $config)
    {
        if (!isset($config['auth'])) $config['auth'] = null;

        switch ($config['auth']) {
            case 'publicKeyFile':
                return $this->createIdRSA($config);
            case 'pem':
                return $this->createPem($config);
            case 'password':
                return $this->createPassword($config);
        }
    }

    protected function createPem(array $config)
    {
        $key = new \Crypt_RSA();
        $key->loadKey(file_get_contents($config['pemFile']));
        return $key;
    }

    protected function createIdRSA(array $config)
    {
        $key = new \Crypt_RSA();
        $key->setPassword(isset($config['passphrase']) ? $config['passphrase'] : null);
        $key->loadKey(file_get_contents(isset($config['privateKeyFile']) ? $config['privateKeyFile'] : getenv("HOME") . '/.ssh/id_rsa'));
        return $key;
    }

    protected function createPassword(array $config)
    {
        return isset($config['password']) ? $config['password'] : null;
    }
}
