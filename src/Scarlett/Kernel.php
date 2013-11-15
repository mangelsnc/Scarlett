<?php

namespace Scarlett;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Scarlett\Event\ResponseEvent;
use Scarlett\Event\KernelEvents;

class Kernel
{
    private $dispatcher;
    private $matcher;
    private $resolver;

    public function __construct(EventDispatcherInterface $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $resolver)
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

            if(!$response instanceof Response){
                $e = new \Exception('Controller must return a Response Object', 100);
                $this->dispatch(KernelEvents::EXCEPTION, new ExceptionEvent($request, $e));                
            }

        }catch(ResourceNotFoundException $e) {
            $response = new Response('Not found', 404);
        }catch(\Exception $e){
            $this->dispatcher->dispatch(KernelEvents::EXCEPTION, new ExceptionEvent($request, $e));
            $this->handleException($e, $request);
        }
        $this->dispatcher->dispatch(KernelEvents::RESPONSE, new ResponseEvent($request, $response));

        return $response;
    }

    public function handleException(\Exception $e, Request $request){
        return new Response("[Scarlett Exception] " . $e->getMessage(), 500);
    }
    
}