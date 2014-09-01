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
        $plugin = new ServerPlugin();

        //$this->assertInstanceOf('Automaton\Server\SshServer', $plugin->server('test', array('host' => 'www.khepri.be')));
        $this->markTestIncomplete(
            'This test needs refactoring.'
        );
    }
}