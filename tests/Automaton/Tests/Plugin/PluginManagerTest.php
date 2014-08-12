<?php


namespace Automaton\Tests\Plugin;


use Automaton\Plugin\PluginManager;

class PluginManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function addsSelfAsPlugin()
    {
        $pluginManager = new PluginManager();
        $this->assertEquals($pluginManager, $pluginManager->get('plugin'));
    }

    /**
     * @test
     */
    public function canBeCreatedWithDefaultPlugins()
    {
        $mockPlugin = $this->getMock('Automaton\Plugin\PluginInterface', array('mock', 'get', 'all'));

        $pluginManager = new PluginManager(array($mockPlugin));
        $this->assertEquals($mockPlugin, $pluginManager->get('mock'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No instance found for fake
     *
     */
    public function missingPluginThrowsInvalidArgumentException()
    {
        $pluginManager = new PluginManager();
        $pluginManager->get('fake');
    }
} 