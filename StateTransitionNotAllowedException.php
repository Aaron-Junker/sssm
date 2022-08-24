<?php

namespace sssm;

use Exception;

/**
 * Exception thrown when a state is not allowed to be entered.
 */
class StateTransitionNotAllowedException extends Exception
{
    public function __construct(State $oldState, State $newState, string $additionalInformation = "")
    {
        $oldStateName = $oldState->getStateName();
        $newStateName = $newState->getStateName();
        $additionalInformation = $additionalInformation ? " $additionalInformation" : "";
        parent::__construct("State transition from \"$oldStateName\" to \"$newStateName\" not allowed.".$additionalInformation);
    }
}