<?php


namespace Deployer\Tests\Task;


use Deployer\Task\GroupTask;

class GroupTaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function hasSubTasks()
    {
        $tasks = array('deploy:init', 'deploy:update_code');
        $task = new GroupTask('deploy', '', $tasks);
        $this->assertEquals($tasks, $task->getTasks());
    }

} 