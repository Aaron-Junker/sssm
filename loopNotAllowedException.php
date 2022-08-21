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
        parent::__construct($state, $state, "Looping is not allowed in state: \".$state->stateName.\".");
    }
}