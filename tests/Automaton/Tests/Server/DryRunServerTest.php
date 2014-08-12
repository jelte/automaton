<?php


namespace Automaton\Tests\Server;


use Automaton\Server\DryRunServer;

class DryRunServerTest extends \PHPUnit_Framework_TestCase
{
    private $dryRunServer;

    private $server;

    private $output;

    private $uri;

    private $connection;

    private $configuration;

    public function setUp()
    {
        $this->uri = $this->getMock('Automaton\Utils\Uri', array(), array('ssh://user@mydomain:22/deploy'));
        $this->connection = $this->getMock('Automaton\Server\Ssh\PhpSeclib');
        $this->configuration = $this->getMock('Automaton\Server\Ssh\Configuration');
        $this->server = $this->getMock('Automaton\Server\SshServer', array(), array('Dummy', $this->uri, $this->connection, $this->configuration));
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->dryRunServer = new DryRunServer($this->server, $this->output);
    }

    /**
     * @test
     */
    public function canRunCommand()
    {
        $this->output->expects($this->once())->method('writeln');
        $this->dryRunServer->run('echo \'Test\'');
    }

    /**
     * @test
     */
    public function canUploadFiles()
    {
        $this->output->expects($this->once())->method('writeln');
        $this->dryRunServer->upload('a','b');
    }


    /**
     * @test
     */
    public function canSetSshKeys()
    {
        $this->dryRunServer->privateKey('~/.ssh/id_rsa');
        $this->dryRunServer->passPhrase('passPhrase');
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function canGetInformation()
    {
        $name = 'Dummy';
        $this->server->expects($this->once())->method('getName')->will($this->returnValue($name));
        $this->assertEquals($name, $this->dryRunServer->getName());

        $this->server->expects($this->once())->method('getUri')->will($this->returnValue($this->uri));
        $this->assertEquals($this->uri, $this->dryRunServer->getUri());
    }

} 