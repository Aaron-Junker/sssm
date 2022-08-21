<?php

namespace sssm;

final class stateMachine
{
    public function __construct(private ?state $startState, state ...$states)
    {
        $this->states = $states;
        $this->currentState = $this->startState;
        foreach ($this->startState->onStateEnter as $callback) {
            $callback($this->startState);
        }
    }

    /**
     * @var ?state The current state.
     */
    private ?state $currentState = null;

    private array $states = [];

    /**
     * @return state The current state.
     */
    public function getCurrentState(): state {
        return $this->currentState;
    }

    public function getCurrentStateName(): string {
        return $this->currentState->stateName;
    }

    /**
     * @param state $newState State to switch to.
     * @return bool true if state changing succeeded.
     * @throws stateTransitionNotAllowedException
     */
    public function switchState(state $newState):bool{
        if(!in_array($newState, $this->currentState->allowedStatesAfter, true)){
            throw new stateTransitionNotAllowedException($this->currentState, $newState);
        }
        foreach ($this->currentState->onStateLeave as $callback) {
            $callback($this->currentState);
        }
        $this->currentState = $newState;
        foreach ($this->currentState->onStateEnter as $callback) {
            $callback($this->currentState);
        }
        return true;
    }
}