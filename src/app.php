<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
    'year' => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction'
)));

$routes->add('prime_number', new Routing\Route('is-prime-number/{number}', array(
    'number' => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::primeAction'
)));

return $routes;