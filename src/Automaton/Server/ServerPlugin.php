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
        if ( !isset($config['auth']) ) $config['auth'] = null;
        if ( !isset($config['username'])) $config['username'] = null;
        $session = new \Net_SFTP($config['host'], isset($config['options']['PORT'])?$config['options']['PORT']:22);
        $key = in_array($config['auth'], array('publicKeyFile', 'pem')) ? $key = new \Crypt_RSA() : null;
        switch ( $config['auth'] ) {
            case 'publicKeyFile':
                $key->setPassword(isset($config['passphrase'])?$config['passphrase']:null);
                $key->loadKey(file_get_contents(isset($config['privateKeyFile'])?$config['privateKeyFile']:getenv("HOME").'/.ssh/id_rsa'));
                break;
            case 'pem':
                $key->loadKey(file_get_contents($config['pemFile']));
                break;
            case 'password':
                $key = $config['password'];
                break;
        }

        $session->login($config['username'], $key);
        $connection = new PhpSecLibConnection($session);
        return $this->registerInstance($name, new SshServer($name, $connection, array_key_exists('path', $config) ? $config['path'] : null));
    }
}
