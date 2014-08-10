<?php


namespace Deployer\Tests\Stage;


use Deployer\Stage\StagePlugin;

class StagePluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StagePlugin
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new StagePlugin();
    }

    /**
     * @test
     */
    public function canCreateStages()
    {
        $this->assertInstanceOf('Deployer\Stage\Stage', $this->plugin->stage('test', array('server-1')));
        $this->assertInstanceOf('Deployer\Stage\Stage', $this->plugin->stage('test', array('server-1'), array('branch' => 'develop')));
        $this->assertInstanceOf('Deployer\Stage\Stage', $this->plugin->stage('test', array('server-1'), array('branch' => 'develop'), true));
        $this->assertNotNull($this->plugin->getDefaultInstance());
    }

    /**
     * @test
     */
    public function canListAllCreatedStages()
    {
        $this->plugin->stage('develop', array('server-1'));
        $this->plugin->stage('stage', array('server-1'), array('branch' => 'develop'));
        $this->plugin->stage('production', array('server-1'), array('branch' => 'develop'), true);
        $this->assertInternalType('array', $this->plugin->all());
        $this->assertCount(3, $this->plugin->all());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function gettingNullInstanceWillThrowException()
    {
        $this->plugin->get(null);
    }
}