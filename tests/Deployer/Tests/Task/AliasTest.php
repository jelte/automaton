<?php


namespace Deployer\Tests\Task;


use Deployer\Task\Alias;

class AliasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createdWithFunction()
    {
        $original = $this->getMock('Deployer\Task\Task', array(), array(), '', false);

        $alias = new Alias('deploy_prod', $original);
        $this->assertEquals($original, $alias->getOriginal());
    }

} 