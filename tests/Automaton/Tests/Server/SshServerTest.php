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
        $this->uri = $this->getMock('Automaton\Utils\Uri', array(), array('ssh://user@mydomain:22/deploy'), '', false);
        $this->connection = $this->getMock('Automaton\Server\Ssh\PhpSeclib');
        $this->configuration = $this->getMock('Automaton\Server\Ssh\Configuration');
        $this->sshServer = new SshServer('Dummy', $this->uri, $this->connection, $this->configuration);
    }

    /**
     * @test
     */
    public function canRunCommand()
    {
        $this->connection->expects($this->once())->method('isInitialized')->will($this->returnValue(false));

        $this->connection->expects($this->once())->method('init');
        $this->connection->expects($this->once())->method('run');
        $this->sshServer->run('echo \'Test\'');
    }

    /**
     * @test
     */
    public function canUploadFiles()
    {

        $this->connection->expects($this->exactly(4))->method('isInitialized')->will($this->onConsecutiveCalls(
            false,
            true,
            true,
            true
        ));

        $this->connection->expects($this->once())->method('init');

        $this->connection->expects($this->exactly(2))->method('upload');
        $this->sshServer->upload('local', 'remote');

        $this->connection->expects($this->once())->method('mkdir');
        $this->sshServer->upload('local', 'path/remote');
    }


    /**
     * @test
     */
    public function canSetSshKeys()
    {
        $this->configuration->expects($this->once())->method('setPrivateKey');
        $this->configuration->expects($this->once())->method('setPassPhrase');
        $this->sshServer->privateKey('~/.ssh/id_rsa');
        $this->sshServer->passPhrase('passPhrase');
    }

    /**
     * @test
     */
    public function canGetInformation()
    {
        $this->assertEquals('Dummy', $this->sshServer->getName());

        $this->assertEquals($this->uri, $this->sshServer->getUri());
    }


} 