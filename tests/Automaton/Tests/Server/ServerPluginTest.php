<?php


namespace Automaton\Tests\Server;


use Automaton\Server\ServerPlugin;

class ServerPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function canCreateServers()
    {
        $plugin = new ServerPlugin(array('ssh' => 'Automaton\Server\SshServer'));

        $this->assertInstanceOf('Automaton\Server\SshServer', $plugin->server('test', array('host' => 'www.khepri.be')));
    }
}