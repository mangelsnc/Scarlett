<?php

namespace Scarlett\Event;

/**
 * @author Miguel Ángel Sánchez <mangel.snc@gmail.com>
 *
 * This Class is used with the only purpose of agrupate
 * all the Event codes in constants to avoid big changes
 * when a event code changes.
 */

class KernelEvents
{
    const REQUEST = "kernel.request";
    const RESPONSE = "kernel.response";
    const EXCEPTION = "kernel.exception";
}