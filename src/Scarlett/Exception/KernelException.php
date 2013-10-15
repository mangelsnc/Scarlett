<?php

namespace Scarlett\Exception;

class KernelException extends \Exception
{
    public function __construct()
    {
        parent::__construct('[Scarlett Kernel Exception] Critical Error', 100);
    }
}