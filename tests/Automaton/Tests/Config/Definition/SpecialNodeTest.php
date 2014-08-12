<?php


namespace Automaton\Test\Config\Definition;


use Automaton\Config\Definition\SpecialNode;

class SpecialNodeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SpecialNode
     */
    protected $specialNode;

    public function setUp()
    {
        $this->specialNode = new SpecialNode('special');
    }

    /**
     * @test
     */
    public function canHandleNullValues()
    {
        $values = array('test');
        $this->assertNull($this->specialNode->merge(null, null));
        $this->assertEquals($values, $this->specialNode->merge($values, null));
        $this->assertEquals($values, $this->specialNode->merge(null, $values));
    }

    /**
     * @test
     */
    public function returnsUniqueValues()
    {
        $values1 = array('test', 'ok');
        $values2 = array('test', 'nok');
        $this->assertCount(3, $this->specialNode->merge($values1, $values2));
    }

    /**
     * @test
     */
    public function doesNotUniqueValuesForKeyValuePairs()
    {
        $values1 = array('test' => 'test', 'ok' => 'ok');
        $values2 = array('test2' => 'test', 'nok' => 'nok');
        $this->assertCount(4, $this->specialNode->merge($values1, $values2));
        $this->assertCount(2, $this->specialNode->merge($values1, $values1));
    }
} 