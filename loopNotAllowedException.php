<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be looped.
 */
class loopNotAllowedException extends stateTransitionNotAllowedException
{
    public function __construct(state $state)
    {
        Exception::__construct("Looping is not allowed in state: \".$state->stateName.\".");
        parent::__construct($state, $state);
    }
}