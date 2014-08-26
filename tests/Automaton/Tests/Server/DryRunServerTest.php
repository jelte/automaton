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
        $this->connection = $this->getMock('Automaton\Server\Ssh\ConnectionInterface');
        $this->server = $this->getMock('Automaton\Server\SshServer', array(), array('Dummy', $this->connection));
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
    public function canGetInformation()
    {
        $name = 'Dummy';
        $this->server->expects($this->once())->method('getName')->will($this->returnValue($name));
        $this->assertEquals($name, $this->dryRunServer->getName());
    }

} 