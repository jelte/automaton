<?php


namespace Automaton\Tests\DepencencyInjection;


use Automaton\DependencyInjection\AutomatonCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutomatonCompilerPassTest extends \PHPUnit_Framework_TestCase
{

    protected $compilerPass;

    /** @var ContainerBuilder */
    protected $containerBuilder;

    /**
     * @var \Symfony\Component\Config\Definition\Processor
     */
    protected $mockProcessor;

    protected $config = array(
        'server' => array('develop' => array('host' => 'www.khepri.be')),
        'stage' => array('development' => array('develop')),
        'task' => null
    );

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->mockProcessor = $this->getMock('Symfony\Component\Config\Definition\Processor');
        $this->compilerPass = new AutomatonCompilerPass(null, $this->mockProcessor);

    }

    /**
     * @test
     */
    public function processSkipsWhenServiceNotDefined()
    {
        $this->mockProcessor->expects($this->never())->method('processConfiguration')->will($this->returnValue($this->config));

        $this->compilerPass->process($this->containerBuilder);
    }


    /**
     * @test
     */
    public function processAddsMethodCallsToService()
    {
        $this->containerBuilder->register('automaton', 'Automaton\Automaton');

        $this->containerBuilder->register('automaton.plugin.server', 'Automaton\Server\ServerPlugin');
        $this->containerBuilder->register('automaton.plugin.stage', 'Automaton\Stage\StagePlugin');
        $this->mockProcessor->expects($this->once())->method('processConfiguration')->will($this->returnValue($this->config));

        $this->compilerPass->process($this->containerBuilder);

        $methodCalls = $this->containerBuilder->getDefinition('automaton')->getMethodCalls();

        $this->assertCount(5, $methodCalls);
    }
} 