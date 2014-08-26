<?php


namespace Automaton\Tests\Server;


use Automaton\Server\SshServer;
use Automaton\Utils\Uri;
use Automaton\Server\Ssh\PhpSecLib;
use Automaton\Server\Ssh\Configuration;

class SshServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SshServer
     */
    private $sshServer;

    private $uri;

    private $connection;

    private $configuration;

    public function setUp()
    {
        $this->connection = $this->getMock('Automaton\Server\Ssh\ConnectionInterface');
        $this->sshServer = new SshServer('Dummy', $this->connection);
    }

    /**
     * @test
     */
    public function canRunCommand()
    {
        $this->connection->expects($this->once())->method('run');
        $this->sshServer->run('echo \'Test\'');
    }

    /**
     * @test
     */
    public function canUploadFiles()
    {
        $this->connection->expects($this->once())->method('upload');
        $this->sshServer->upload('local', 'remote');
    }

    /**
     * @test
     */
    public function canGetInformation()
    {
        $this->assertEquals('Dummy', $this->sshServer->getName());
    }


} 