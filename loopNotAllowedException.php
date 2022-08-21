<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be looped.
 */
class loopNotAllowedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Looping is not allowed in this state.");
    }
}