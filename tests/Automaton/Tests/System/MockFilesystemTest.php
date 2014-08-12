<?php


namespace Automaton\Tests\System;


use Automaton\System\DryRunSystem;
use Automaton\System\MockFilesystem;
use Automaton\System\System;


class MockFilesystemTest extends \PHPUnit_Framework_TestCase
{
    protected $output;

    /** @var MockFilesystem */
    protected $system;

    public function setUp()
    {
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->system = new MockFilesystem($this->output);
    }

    /**
     * @test
     */
    public function outputsCalls()
    {
        $this->output->expects($this->once())->method('writeln')->with('mkdir test');
        $this->system->mkdir('test');
    }
} 