<?php


namespace Deployer\Tests\Stage;


use Deployer\Runner\Runner;

class RunnerTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Runner
     */
    protected $runner;

    protected $input;

    protected $output;

    public function setUp()
    {
        $this->runner = new Runner();
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $this->output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->runner->setUp($this->input, $this->output);
    }

    /**
     * @test
     */
    public function canHaveServers()
    {
        $this->runner->setServers(array(
            'server-1' => $this->getMock('Deployer\Server\ServerInterface'),
            'server-2' => $this->getMock('Deployer\Server\ServerInterface')
        ));

        $this->assertInternalType('array', $this->runner->getServers());
        $this->assertCount(2, $this->runner->getServers());
    }

    /**
     * @test
     */
    public function doesRunTaskWithServers()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));

        $this->runner->run($this->createSimpleTask());
    }

    /**
     * @test
     */
    public function invokesMethodsWithParams()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));

        $task = $this->getMock('Deployer\Task\ExecutableTaskInterface');
        $method = $this->getMock('\ReflectionMethod', array(), array(), '', false);


        $serverParam = $this->getMock('\ReflectionParameter', array(), array(), '', false);
        $serverParam->expects($this->once())->method('getName')->willReturn('server');
        $inputParam = $this->getMock('\ReflectionParameter', array(), array(), '', false);
        $inputParam->expects($this->once())->method('getName')->willReturn('input');
        $outputParam = $this->getMock('\ReflectionParameter', array(), array(), '', false);
        $outputParam->expects($this->once())->method('getName')->willReturn('output');
        $method->expects($this->once())->method('getParameters')->willReturn(array(
            $serverParam, $inputParam, $outputParam
        ));
        $task->expects($this->once())->method('getCallable')->willReturn($method);
        $task->expects($this->once())->method('getBefore')->willReturn(array());
        $task->expects($this->once())->method('getAfter')->willReturn(array());

        $this->runner->run($task);
    }

    /**
     * @test
     */
    public function runsTasksBeforeWithServers()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));

        $this->runner->run($this->createSimpleTask(array($this->createSimpleTask())));
    }

    /**
     * @test
     */
    public function runsTasksAfterWithServers()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));

        $this->runner->run($this->createSimpleTask(array(), array($this->createSimpleTask())));
    }


    /**
     * @test
     */
    public function doesRunGroupTaskWithServers()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));


        $groupTask = $this->getMock('Deployer\Task\GroupTaskInterface', array(), array());
        $groupTask->expects($this->once())->method('getTasks')->willReturn(array($this->createSimpleTask(), $this->createSimpleTask()));
        $groupTask->expects($this->once())->method('getBefore')->willReturn(array($this->createSimpleTask()));
        $groupTask->expects($this->once())->method('getAfter')->willReturn(array($this->createSimpleTask()));

        $this->runner->run($groupTask);
    }

    /**
     * @test
     */
    public function doesRunAliasWithServers()
    {
        $this->runner->setServers(array('server-1' => $this->getMock('Deployer\Server\ServerInterface')));

        $alias = $this->getMock('Deployer\Task\AliasInterface', array(), array());
        $alias->expects($this->once())->method('getOriginal')->willReturn($this->createSimpleTask());
        $alias->expects($this->once())->method('getBefore')->willReturn(array($this->createSimpleTask()));
        $alias->expects($this->once())->method('getAfter')->willReturn(array($this->createSimpleTask()));

        $this->runner->run($alias);
    }

    /**
     * @test
     */
    public function doesNotRunTaskWithoutServers()
    {
        $this->runner->run($this->createSimpleTask(array(), array(), false));
    }

    /**
     * @test
     */
    public function runsTasksBeforeWithoutServers()
    {
        $this->runner->run($this->createSimpleTask(array($this->createSimpleTask(array(), array(), false)), array(), false));
    }

    /**
     * @test
     */
    public function runsTasksAfterWithoutServers()
    {
        $this->runner->run($this->createSimpleTask(array(), array($this->createSimpleTask(array(), array(), false)), false));
    }

    private function createSimpleTask(array $before = array(), array $after = array(), $executes = true)
    {
        $task = $this->getMock('Deployer\Task\ExecutableTaskInterface');
        if ( $executes ) {
            $method = $this->getMock('\ReflectionMethod', array(), array(), '', false);
            $method->expects($this->once())->method('getParameters')->willReturn(array());
            $task->expects($this->once())->method('getCallable')->willReturn($method);
        }
        $task->expects($this->once())->method('getBefore')->willReturn($before);
        $task->expects($this->once())->method('getAfter')->willReturn($after);

        return $task;
    }
}