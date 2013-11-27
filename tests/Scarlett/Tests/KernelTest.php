<?php

namespace Scarlett\Tests;

use Scarlett\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
 
class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturn404OnNotFoundException()
    {
        $kernel = $this->getKernelForNotFoundException();
 
        $response = $kernel->handle(new Request());
 
        $this->assertEquals(404, $response->getStatusCode());
    }

    protected function getKernelForNotFoundException()
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException(new ResourceNotFoundException()))
        ;
        $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
 
        return new Kernel($dispatcher, $matcher, $resolver);
    }

    public function testShouldReturn500OnException()
    {
        $kernel = $this->getKernelForException();
 
        $response = $kernel->handle(new Request());
 
        $this->assertEquals(
            '[Scarlett Exception] Controller must return a Response Object', 
            $response->getContent()
        );
    }

    private function getKernelForException()
    {
        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->returnValue(array(
                '_controller' => 'Fake\\Controller\\FakeController::fakeMethod',
                '_route' => 'fake_route'
            )
        ));

        $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $resolver
            ->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(function(){}))
        ;

        $resolver
            ->expects($this->once())
            ->method('getArguments')
            ->will($this->returnValue(array()))
        ;

        return new Kernel($dispatcher, $matcher, $resolver);
    }

}