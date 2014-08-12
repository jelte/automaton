<?php


namespace Automaton\Tests;


use Automaton\Automaton;

class AutomatonTest extends \PHPUnit_Framework_TestCase
{
    private $pluginManager;

    private $automaton;

    public function setUp()
    {
        $this->pluginManager = $this->getMock('Automaton\Plugin\PluginManager', array('get', 'all'));

        $this->automaton = new Automaton($this->pluginManager);
    }

    /**
     * @test
     */
    public function canRegisterPlugin()
    {
        $mockPlugin = $this->getMock('Automaton\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->will($this->returnValue(
                $this->pluginManager
            ));
        $this->automaton->plugin($mockPlugin);
    }

    /**
     * @test
     */
    public function canCallPluginInstanceConstructorsAsMethods()
    {
        $mockPlugin = $this->getMock('Automaton\Plugin\PluginInterface', array('mock', 'get', 'all'));
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
        $this->automaton->plugin($mockPlugin);

        $this->automaton->mock();
    }

    /**
     * @test
     */
    public function canGetPluginInstance()
    {
        $mockPlugin = $this->getMock('Automaton\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->withConsecutive(
                array($this->equalTo('mock'))
            )
            ->will($this->onConsecutiveCalls(
                $mockPlugin
            ));

        $mockPlugin->expects($this->once())->method('get')->will($this->returnValue('test'));
        $this->automaton->get('mock','test');
    }

    /**
     * @test
     */
    public function canGetAllPluginInstances()
    {
        $mockPlugin = $this->getMock('Automaton\Plugin\PluginInterface', array('mock', 'get', 'all'));
        $this->pluginManager->expects($this->once())
            ->method('get')
            ->withConsecutive(
                array($this->equalTo('mock'))
            )
            ->will($this->onConsecutiveCalls(
                $mockPlugin
            ));

        $mockPlugin->expects($this->once())->method('all')->will($this->returnValue(array()));
        $this->automaton->all('mock');
    }
}