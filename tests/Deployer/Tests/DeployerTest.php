<?php


namespace Deployer\Tests;


use Deployer\Deployer;

class DeployerTest extends \PHPUnit_Framework_TestCase
{
    private $pluginManager;

    private $deployer;

    public function setUp()
    {
        $this->pluginManager = $this->getMock('Deployer\Plugin\PluginManager', array('get', 'all'));

        $this->deployer = new Deployer($this->pluginManager);
    }

    /**
     * @test
     */
    public function canRegisterPlugin()
    {
        $mockPlugin = $this->getMock('Deployer\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue(
                $this->pluginManager
            ));
        $this->deployer->plugin($mockPlugin);
    }

    /**
     * @test
     */
    public function canCallPluginInstanceConstructorsAsMethods()
    {
        $mockPlugin = $this->getMock('Deployer\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                array($this->equalTo('plugin')),
                array($this->equalTo('mock'))
            )
            ->will($this->onConsecutiveCalls(
                $this->pluginManager,
                $mockPlugin
            ));
        $this->deployer->plugin($mockPlugin);

        $this->deployer->mock();
    }

    /**
     * @test
     */
    public function canGetPluginInstance()
    {
        $mockPlugin = $this->getMock('Deployer\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->withConsecutive(
                array($this->equalTo('mock'))
            )
            ->will($this->onConsecutiveCalls(
                $mockPlugin
            ));

        $mockPlugin->expects($this->once())->method('get')->will($this->returnValue('test'));
        $this->deployer->get('mock','test');
    }

    /**
     * @test
     */
    public function canGetAllPluginInstances()
    {
        $mockPlugin = $this->getMock('Deployer\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->withConsecutive(
                array($this->equalTo('mock'))
            )
            ->will($this->onConsecutiveCalls(
                $mockPlugin
            ));

        $mockPlugin->expects($this->once())->method('all')->will($this->returnValue(array()));
        $this->deployer->all('mock');
    }
}