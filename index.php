<?php

namespace sssm;

use Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 *
 */
final class state {
    public bool $stateInitialized = false;
    private bool $canLoop;
    /**
     * @param array $allowedStatesAfter The states that can be transitioned to from this state.
     * @param bool $canLoop Sets if looping the state is allowed
     */
    final public function __construct(array $allowedStatesAfter, bool $canLoop = true){
        $this->allowedStatesAfter = $allowedStatesAfter;
        $this->canLoop = $canLoop;
    }

    final public function initState(){
        $newAllowedStatesAfter = [];
        if(!$this->stateInitialized){
            foreach ($this->allowedStatesAfter as $state) {
                global $$state;
                $newAllowedStatesAfter[] = $$state;
            }
            $this->allowedStatesAfter = $newAllowedStatesAfter;
        }
        $this->stateInitialized = true;
        if($this->canLoop){
            $this->allowedStatesAfter[] = $this;
        }
    }

    /**
     * @return void Starts the state from the beginning
     * @throws Exception
     */
    final public function loop(): void {
        if($this->canLoop) {
            foreach ($this->onLoop as $callback) {
                $callback();
            }
            foreach ($this->onStateEnter as $callback) {
                $callback();
            }
        }else{
            throw new Exception("Looping is not allowed in this state");
        }
    }

    /**
     * @var array An array of allowed states after this state.
     */
    public array $allowedStatesAfter = [];
    /**
     * @var array An array of functions called when the state is entered.
     */
    public array $onStateEnter = [];
    /**
     * @var array An array of functions called when the state is looped.
     */
    public array $onLoop = [];
    /**
     * @var array An array of functions called when the state is exited.
     */
    public array $onStateLeave = [];

}

/**
 * A state machine
 */
final class stateMachine {
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

/**
 * Exception thrown when a state is not allowed to be entered.
 */
class stateNotAllowedException extends Exception {
    /**
     * @param state $fromState State fom which the state change is attempted.
     * @param state $toState State that is not allowed to be entered.
     */
    public function __construct(state $fromState, state $toState)
    {
        parent::__construct("Not allowed state transition.");
    }
}


