<?php

namespace sssm;

final class StateMachine
{
    public function __construct(private ?State $startState, State ...$states)
    {
        $this->states = $states;
        $this->states[] = $startState;
        $this->currentState = $this->startState;
        foreach ($this->startState->onStateEnter as $callback) {
            $callback($this->startState);
        }
    }

    private ?State $currentState = null;

    /**
    * @var State[] An array of all states that are registered in the state machine.
     */
    private array $states = [];

    /**
     * @return State[] Returns an array of all states that are registered in the state machine.
     */
    public function getRegisteredStates(): array {
        return $this->states;
    }

    /**
     * @return State
     */
    public function getCurrentState(): State {
        return $this->currentState;
    }

    /**
     * @return string
     */
    public function getCurrentStateName(): string {
        return $this->currentState->getStateName();
    }

    /**
     * @param State $newState
     * @return bool true if state changing succeeded.
     * @throws StateTransitionNotAllowedException
     * @throws StateNotRegisteredInStateMachineException
     */
    public function switchState(State $newState):bool{
        if(!in_array($newState, $this->states, true)){
            throw new StateNotRegisteredInStateMachineException($this->currentState, $newState);
        }
        if(!in_array($newState, $this->currentState->getAllowedStateTransitions(), true)){
            throw new StateTransitionNotAllowedException($this->currentState, $newState);
        }
        foreach ($this->currentState->onStateLeave as $callback) {
            $callback($this->currentState);
        }
        if($this->currentState = $newState){
            foreach ($this->currentState->onLoop as $callback) {
                $callback($this->currentState);
            }
        }
        $this->currentState = $newState;
        foreach ($this->currentState->onStateEnter as $callback) {
            $callback($this->currentState);
        }
        return true;
    }

    /**
     * @return void Starts the state from the beginning.
     * @throws LoopNotAllowedException
     */
    public function loop(): void {
        if($this->currentState->getCanLoop()) {
            foreach ($this->currentState->onStateLeave as $callback) {
                $callback($this);
            }
            foreach ($this->currentState->onLoop as $callback) {
                $callback($this);
            }
            foreach ($this->currentState->onStateEnter as $callback) {
                $callback($this);
            }
        }else{
            throw new LoopNotAllowedException($this->currentState);
        }
    }
}