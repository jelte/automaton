<?php


namespace Deployer\Tests\Task;


use Deployer\Task\TaskPlugin;

class TaskPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaskPlugin
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new TaskPlugin();
    }

    /**
     * @test
     */
    public function canCreateTaskWithClosure()
    {
        $this->assertInstanceOf('Deployer\Task\Task', $this->plugin->task('test', function() {}));
    }

    /**
     * @test
     */
    public function canCreateTaskWithCallable()
    {
        $this->assertInstanceOf('Deployer\Task\Task', $this->plugin->task('deploy:test', array($this->plugin, 'task')));
    }

    /**
     * @test
     */
    public function canCreateGroupTask()
    {
        $this->plugin->task('test', function() {});
        $this->assertInstanceOf('Deployer\Task\GroupTask', $this->plugin->task('deploy:test', array('test')));
    }

    /**
     * @test
     * @expectedException \Deployer\Exception\InvalidArgumentException
     * @expectedExceptionMessage Can not create a task from the given callable
     */
    public function canNotCreateAliasThroughTaskMethod()
    {
        $this->plugin->task('test', function() {});
        $this->plugin->task('deploy:test', 'test');
    }

    /**
     * @test
     */
    public function canCreateAlias()
    {
        $this->plugin->task('test', function() {});
        $this->assertInstanceOf('Deployer\Task\Alias', $this->plugin->alias('deploy:test', 'test'));
    }
}