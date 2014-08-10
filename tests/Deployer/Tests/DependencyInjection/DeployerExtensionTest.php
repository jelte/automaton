<?php


namespace Deployer\Tests\DepencencyInjection;


use Deployer\DependencyInjection\DeployerExtension;

class DeployerExtensionTest extends \PHPUnit_Framework_TestCase {

    protected $loaderResolver;

    protected $processor;

    protected $configuration;

    protected $deployerExtension;

    protected $container;

    protected $loader;

    public function setUp()
    {
        $this->loaderResolver = $this->getMock('Symfony\Component\Config\Loader\LoaderResolverInterface');
        $this->processor = $this->getMock('Symfony\Component\Config\Definition\Processor');
        $this->configuration = $this->getMock('Symfony\Component\Config\Definition\ConfigurationInterface');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->loader = $this->getMock('Symfony\Component\Config\Loader\LoaderInterface');

        $this->deployerExtension = new DeployerExtension($this->loaderResolver, $this->processor, $this->configuration);
    }

    /**
     * @test
     */
    public function canLoadPlugins()
    {
        $this->processor->expects($this->once())->method('processConfiguration')->willReturn(array(
            'task' => array(
                '_config' => getcwd().'/src/Deployer/Resources/configs'
            ),
            'server' => array()
        ));
        $this->container->expects($this->exactly(2))->method('getParameter')->willReturn(getcwd().'/src/Deployer/Resources/configs');
        $this->loaderResolver->expects($this->exactly(2))->method('resolve')->willReturn($this->loader);
        $this->loader->expects($this->exactly(2))->method('load');
        $this->deployerExtension->load(array(), $this->container);
    }
} 