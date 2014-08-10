<?php


namespace Deployer\Tests\Server;


use Deployer\Server\ServerPlugin;

class ServerPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function canCreateServers()
    {
        $plugin = new ServerPlugin(array('ssh' => 'Deployer\Server\SshServer'));

        $this->assertInstanceOf('Deployer\Server\SshServer', $plugin->server('test', 'ssh://www.khepri.be'));
    }
    /**
     * @test
     * @expectedException \Deployer\Exception\InvalidArgumentException
     */
    public function undefinedProtocolWillThrowException()
    {
        $plugin = new ServerPlugin(array('ssh' => 'Deployer\Server\SshServer'));

        $this->assertInstanceOf('Deployer\Server\SshServer', $plugin->server('test', 'ftp://www.khepri.be'));
    }
}