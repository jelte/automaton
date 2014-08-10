<?php


namespace Deployer\Tests\Util;


use Deployer\Utils\Uri;

class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function welformedFullUri()
    {
        $uri = new Uri('ssh://user:password@www.domain.com:22/~/path');

        $this->assertEquals('ssh', $uri->getScheme());
        $this->assertEquals('user', $uri->getLogin());
        $this->assertEquals('password', $uri->getPassword());
        $this->assertEquals('www.domain.com', $uri->getHost());
        $this->assertEquals('22', $uri->getPort());
        $this->assertEquals('~/path', $uri->getPath());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid URI
     */
    public function badUriThrowsException()
    {
        $uri = new Uri('ssh://@www.domain:com');
    }
} 