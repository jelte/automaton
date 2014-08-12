<?php


namespace Automaton\Tests\Stage;


use Automaton\Stage\Stage;

class StageTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Stage
     */
    private $stage;

    public function setUp()
    {
        $this->stage = new Stage('develop', array('develop'), array('branch' => 'develop'));
    }

    /**
     * @test
     */
    public function canGetName()
    {
        $this->assertEquals('develop', $this->stage->getName());
    }

    /**
     * @test
     */
    public function canGetServers()
    {
        $this->assertEquals(array('develop'), $this->stage->getServers());
    }

    /**
     * @test
     */
    public function canGetOptions()
    {
        $this->assertEquals(array('branch' => 'develop'), $this->stage->getOptions());
    }
} 