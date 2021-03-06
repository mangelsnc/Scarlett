<?php

require_once __DIR__."/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Scarlett;
use Scarlett\Event\KernelEvents;
use Scarlett\Listener\ResponseListener;

function render_template($request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__."/../src/pages/%s.php", $_route);

    return new Response(ob_get_clean(), 200);
}

$request = Request::createFromGlobals();
$routes = include __DIR__."/../src/app.php";

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addListener(KernelEvents::RESPONSE, array(new ResponseListener(), 'onResponse'));

$kernel = new Scarlett\Kernel($dispatcher, $matcher, $resolver);
$response = $kernel->handle($request);

$response->send();