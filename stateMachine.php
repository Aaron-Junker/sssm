<?php

namespace sssm;

final class stateMachine
{
    /**
     * @var ?state The current state.
     */
    private ?state $currentState = null;
    /**
     * @var ?state The state the state-machine starts on.
     */
    private ?state $startState = null;

    final public function __construct(state $startState)
    {
        $this->startState = $startState;
        $this->currentState = $this->startState;
        $this->currentState->initState();
        foreach ($this->startState->onStateEnter as $callback) {
            $callback();
        }
    }

    /**
     * @return state|null The current state.
     */

    final public function getCurrentState(): state {
        return $this->currentState;
    }

    /**
     * @param state $newState State to switch to.
     * @return bool true if state changing succeeded.
     * @throws stateNotAllowedException
     */
    final public function switchState(state $newState):bool{
        if(!in_array($newState, $this->currentState->allowedStatesAfter)){
            throw new stateNotAllowedException($this->currentState, $newState);
        }
        $newState->initState();
        foreach ($this->currentState->onStateLeave as $callback) {
            $callback();
        }
        $this->currentState = $newState;
        foreach ($this->currentState->onStateEnter as $callback) {
            $callback();
        }
        return true;
    }
}