<?php


namespace Automaton\Tests\System;


use Automaton\System\System;

class SystemTest extends \PHPUnit_Framework_TestCase
{
    protected $filesystem;

    /** @var System */
    protected $system;

    public function setUp()
    {
        $this->filesystem = $this->getMock('Automaton\System\Filesystem');

        $this->system = new System($this->filesystem, getcwd());
    }

    /**
     * @test
     */
    public function hasAFilesystem()
    {
        $this->assertEquals($this->filesystem, $this->system->getFilesystem());
    }
} 