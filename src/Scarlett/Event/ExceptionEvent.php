<?php

namespace Scarlett\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use

class ExceptionEvent extends Event
{
    private $request;
    private $exception;
    private $response;

    public function __construct(Request $request, \Exception $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
        $this->response = null;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
