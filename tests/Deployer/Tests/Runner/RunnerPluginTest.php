<?php


namespace Deployer\Tests\Stage;


use Deployer\Runner\RunnerPlugin;

class RunnerPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RunnerPlugin
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new RunnerPlugin();
    }

    /**
     * @test
     */
    public function canCreateRunner()
    {
        $this->assertInstanceOf('Deployer\Runner\Runner', $this->plugin->runner());
        $this->assertInstanceOf('Deployer\Runner\Runner', $this->plugin->runner('test'));
        $this->assertEquals($this->plugin->get(), $this->plugin->get('test'));
    }
}