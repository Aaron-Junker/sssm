<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be looped.
 */
class LoopNotAllowedException extends StateTransitionNotAllowedException
{
    public function __construct(State $state)
    {
        $stateName = $state->getStateName();
        parent::__construct($state, $state, "Looping is not allowed in state: \"$stateName\".");
    }
}