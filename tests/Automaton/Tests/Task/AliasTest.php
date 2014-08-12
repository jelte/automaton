<?php


namespace Automaton\Tests\Task;


use Automaton\Task\Alias;

class AliasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createdWithFunction()
    {
        $original = $this->getMock('Automaton\Task\Task', array(), array(), '', false);

        $alias = new Alias('deploy_prod', $original);
        $this->assertEquals($original, $alias->getOriginal());
    }

} 