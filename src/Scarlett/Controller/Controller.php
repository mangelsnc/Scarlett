<?php

namespace Scarlett\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function render($template, $params)
    {
        extract($params, EXTR_SKIP);
        ob_start();
        include sprintf(__DIR__."/../../views/%s.php", $template);

        return new Response(ob_get_clean(), 200);
    }
}