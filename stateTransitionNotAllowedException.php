<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be entered.
 */
class stateTransitionNotAllowedException extends Exception
{
    public function __construct(state $oldState, state $newState, string $additionalInformation = "")
    {
        $oldStateName = $oldState->stateName;
        $newStateName = $newState->stateName;
        parent::__construct("State transition from \"$oldStateName\" to \"$newStateName\" not allowed.\n".$additionalInformation);
    }
}