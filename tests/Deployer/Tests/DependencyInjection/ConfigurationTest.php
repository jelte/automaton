<?php


namespace Deployer\Tests\DepencencyInjection;


use Deployer\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    protected $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration('deployer');
    }

    /**
     * @test
     */
    public function canCreateConfigTreeBuilder()
    {
        $this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder',$this->configuration->getConfigTreeBuilder());
    }

    /**
     * @test
     */
    public function canParseSimpleYamlConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, array(
            array(
                'server' => array('develop' => array('host' => 'www.khepri.be')),
                'stage' => array('development' => array('develop'))
            )
        ));

        $this->assertInternalType('array', $config);
    }

    /**
     * @test
     */
    public function canParseComplexYamlConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, array(
            array(
                'server' => array('develop' => array('host' => 'www.khepri.be')),
                'stage' => array('development' => array('develop'))
            ),
            array(
                'server' => array('stage' => array('host' => 'www.khepri.be')),
                'stage' => array('stage' => array('stage'))
            )
        ));

        $this->assertInternalType('array', $config);
    }
} 