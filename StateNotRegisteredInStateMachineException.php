<?php

namespace sssm;

/**
 * Exception thrown when a state is not allowed to be entered in the current state machine.
 */
class StateNotRegisteredInStateMachineException extends StateTransitionNotAllowedException
{
    public function __construct(State $oldState, State $newState)
    {
        $stateName = $newState->getStateName();
        parent::__construct($oldState, $newState, "State \"$stateName\" is not registered in state machine.");
    }
}