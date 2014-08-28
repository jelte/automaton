<?php


namespace Automaton\Tests\System;


use Automaton\System\DryRunSystem;
use Automaton\System\System;
use Symfony\Component\Console\Output\OutputInterface;


class DryRunSystemTest extends \PHPUnit_Framework_TestCase
{
    protected $output;

    /** @var System */
    protected $system;

    public function setUp()
    {
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->system = new DryRunSystem($this->output);
    }

    /**
     * @test
     */
    public function hasAFilesystem()
    {
        $this->assertInstanceOf('Automaton\System\FilesystemInterface', $this->system->getFilesystem());
    }

    /**
     * @test
     */
    public function outputsRunCommand()
    {
        $command = 'ls -la';
        $this->output->expects($this->once())->method('writeln')->with($command);
        $this->system->run('ls -la');
    }
} 