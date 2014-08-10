<?php


namespace Deployer\Tests\DepencencyInjection;


use Deployer\DependencyInjection\DeployerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DeployerCompilerPassTest extends \PHPUnit_Framework_TestCase
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
        $this->compilerPass = new DeployerCompilerPass(null, $this->mockProcessor);

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
        $this->containerBuilder->register('deployer', 'Deployer\Deployer');

        $this->containerBuilder->register('deployer.plugin.server', 'Deployer\Server\ServerPlugin');
        $this->containerBuilder->register('deployer.plugin.stage', 'Deployer\Server\StagePlugin');
        $this->mockProcessor->expects($this->once())->method('processConfiguration')->will($this->returnValue($this->config));

        $this->compilerPass->process($this->containerBuilder);

        $methodCalls = $this->containerBuilder->getDefinition('deployer')->getMethodCalls();

        $this->assertCount(5, $methodCalls);
    }
} 