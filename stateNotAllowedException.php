<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be entered.
 */
class stateNotAllowedException extends Exception
{
    public function __construct()
    {
        parent::__construct("State transition not allowed.");
    }
}