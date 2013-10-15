<?php

namespace Scarlett;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Scarlett\Event\ResponseEvent;
use Scarlett\Event\KernelEvents;

class Kernel
{
    private $dispatcher;
    private $matcher;
    private $resolver;

    public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->resolver = $resolver;
    }

    public function handle(Request $request)
    {
        try{
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->resolver->getController($request);
            $arguments = $this->resolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        }catch(ResourceNotFoundException $e) {
            $response = new Response('Not found', 404);
        }catch(\Exception $e){
            $response = new Response('[Scarlett Kernel Exception] '.$e->getMessage(), 500);
            $this->dispatcher->dispatch(KernelEvents::EXCEPTION, new ExceptionEvent($request, $e));
        }
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, new ResponseEvent($request, $response));

        return $response;
    }
    
}