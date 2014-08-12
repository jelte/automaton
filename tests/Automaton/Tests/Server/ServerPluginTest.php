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

        $this->assertInstanceOf('Automaton\Server\SshServer', $plugin->server('test', 'ssh://www.khepri.be'));
    }
    /**
     * @test
     * @expectedException \Automaton\Exception\InvalidArgumentException
     */
    public function undefinedProtocolWillThrowException()
    {
        $plugin = new ServerPlugin(array('ssh' => 'Automaton\Server\SshServer'));

        $this->assertInstanceOf('Automaton\Server\SshServer', $plugin->server('test', 'ftp://www.khepri.be'));
    }
}