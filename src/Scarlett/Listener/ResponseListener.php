<?php

namespace Scarlett\Listener;

use Scarlett\Event\ResponseEvent;

class ResponseListener
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->set('X-Developed-With', 'Scarlett');
    }
}