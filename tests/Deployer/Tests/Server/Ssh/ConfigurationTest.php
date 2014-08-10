<?php


namespace Deployer\Tests\Server\Ssh;


use Deployer\Server\Ssh\Configuration;

class ConfigurationTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    protected $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration();
    }

    /**
     * @test
     */
    public function canSetPrivateKey()
    {
        $this->configuration->setPrivateKey('~/.ssh/id_rsa');

        $this->assertEquals('~/.ssh/id_rsa', $this->configuration->getPrivateKey());
    }

    /**
     * @test
     */
    public function canSetPublicKey()
    {
        $this->configuration->setPublicKey('~/.ssh/id_rsa.pub');

        $this->assertEquals('~/.ssh/id_rsa.pub', $this->configuration->getPublicKey());
    }

    /**
     * @test
     */
    public function canSetPassPhrase()
    {
        $this->configuration->setPassPhrase('my-passphrase');

        $this->assertEquals('my-passphrase', $this->configuration->getPassPhrase());
    }

    /**
     * @test
     */
    public function canSetPemFile()
    {
        $this->configuration->setPemFile('file.pem');

        $this->assertEquals('file.pem', $this->configuration->getPemFile());
    }
} 