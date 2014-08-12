<?php


namespace Automaton\Tests\Task;


use Automaton\Task\Task;
use Automaton\Task\TaskPlugin;

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
        $this->assertInstanceOf('Automaton\Task\Task', $this->plugin->task('test', function() {}));
    }

    /**
     * @test
     */
    public function canCreateTaskWithCallable()
    {
        $this->assertInstanceOf('Automaton\Task\Task', $this->plugin->task('deploy:test', array($this->plugin, 'task')));
    }

    /**
     * @test
     */
    public function canCreateGroupTask()
    {
        $this->plugin->task('test', function() {});
        $this->assertInstanceOf('Automaton\Task\GroupTask', $this->plugin->task('deploy:test', array('test')));
    }

    /**
     * @test
     * @expectedException \Automaton\Exception\InvalidArgumentException
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
        $this->assertInstanceOf('Automaton\Task\Alias', $this->plugin->alias('deploy:test', 'test'));
    }

    /**
     * @test
     */
    public function canAddATaskBeforeAnother()
    {
        $test = $this->plugin->task('test', function() {});
        $this->plugin->task('test:init', function() {});
        $this->plugin->before('test', 'test:init');
        $this->assertCount(1, $test->getBefore());
    }

    /**
     * @test
     */
    public function canAddATaskAfterAnother()
    {
        $test = $this->plugin->task('test', function() {});
        $this->plugin->task('test:init', function() {});
        $this->plugin->after('test', 'test:init');
        $this->assertCount(1, $test->getAfter());
    }
}