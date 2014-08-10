<?php


namespace Deployer\Tests\Task;


use Deployer\Task\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{


    protected $task;

    public static function dummy()
    {

    }

    public function setUp()
    {
        $this->task = new Task('deploy', 'Deploy your code', function() {});
    }

    /**
     * @test
     */
    public function canGetName()
    {
        $this->assertEquals('deploy', $this->task->getName());
    }

    /**
     * @test
     */
    public function canGetDescription()
    {
        $this->assertEquals('Deploy your code', $this->task->getDescription());
    }

    /**
     * @test
     */
    public function canChangeDescription()
    {
        $this->task->desc('changed description');
        $this->assertEquals('changed description', $this->task->getDescription());
    }

    /**
     * @test
     */
    public function canGetCallback()
    {
        $this->assertInstanceOf('ReflectionFunction',$this->task->getCallable());
    }

    /**
     * @test
     */
    public function hasTaskToExecuteBefore()
    {
        $this->task->before($this->getMock('Deployer\Task\TaskInterface'));
        $this->assertCount(1, $this->task->getBefore());
    }

    /**
     * @test
     */
    public function hasTaskToExecuteAfter()
    {
        $this->task->after($this->getMock('Deployer\Task\TaskInterface'));
        $this->assertCount(1, $this->task->getAfter());
    }

    /**
     * @test
     */
    public function createdWithFunctionName()
    {
        $task = new Task('deploy', '', 'is_null');
        $this->assertInstanceOf('ReflectionFunction',$task->getCallable());
    }

    /**
     * @test
     */
    public function createdWithStaticMethod()
    {
        $task = new Task('deploy', '', __CLASS__.'::dummy');
        $this->assertInstanceOf('ReflectionMethod',$task->getCallable());
    }

    /**
     * @test
     */
    public function createdWithMethod()
    {
        $task = new Task('deploy', '', array($this, 'createdWithMethod'));
        $this->assertInstanceOf('ReflectionMethod',$task->getCallable());
    }
} 