<?php

namespace sssm;

/**
 * Exception thrown when a state is not allowed to be entered.
 */
class stateNotAllowedException extends \Exception
{
    /**
     * @param state $fromState State fom which the state change is attempted.
     * @param state $toState State that is not allowed to be entered.
     */
    public function __construct(state $fromState, state $toState)
    {
        parent::__construct("Not allowed state transition.");
    }
}