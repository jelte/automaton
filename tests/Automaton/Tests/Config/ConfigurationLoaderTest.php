<?php


namespace Automaton\Tests\Config;


use Automaton\Config\ConfigurationLoader;

class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    protected $configurationLoader;

    protected $resolver;

    protected $loader;

    public function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->resolver = $this->getMock('Symfony\Component\Config\Loader\LoaderResolver');

        $this->loader = $this->getMock('Symfony\Component\DependencyInjection\Loader\FileLoader', array(), array(), '', false);

        $this->container->expects($this->once())->method('set')->with('resolver');
        $this->container->expects($this->once())->method('registerExtension');
        $this->container->expects($this->exactly(2))->method('addCompilerPass');
        $this->configurationLoader = new ConfigurationLoader($this->container, '');
    }

    /**
     * @test
     */
    public function canLoadConfig()
    {
        $this->resolver->expects($this->once())->method('resolve')->willReturn($this->loader);
        $this->container->expects($this->once())->method('get')->with('resolver')->willReturn($this->resolver);
        $this->container->expects($this->once())->method('compile');
        $this->configurationLoader->load('config.yml');
    }
}