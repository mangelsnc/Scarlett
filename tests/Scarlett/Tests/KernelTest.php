<?php

namespace Scarlett\Tests;

use Scarlett\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
 
class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testNotFoundHandling()
    {
        $kernel = $this->getKernelForException(new ResourceNotFoundException());
 
        $response = $kernel->handle(new Request());
 
        $this->assertEquals(404, $response->getStatusCode());
    }
 
    protected function getKernelForException($exception)
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
 
        return new Kernel($dispatcher, $matcher, $resolver);
    }
}