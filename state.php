<?php

namespace sssm;

use Exception;

final class state
{
    private bool $canLoop;

    /**
     * @param array $allowedStatesAfter The states that can be transitioned to from this state.
     * @param bool $canLoop Sets if looping the state is allowed
     * @throws stateNotAllowedException
     */
    final public function __construct(array $allowedStatesAfter=[], bool $canLoop = true){
        $this->allowedStatesAfter = $allowedStatesAfter;
        $this->canLoop = $canLoop;
        if($this->canLoop){
            $this->allowedStatesAfter[] = $this;
        }elseif(in_array($this, $this->allowedStatesAfter)){
            throw new stateNotAllowedException();
        }
    }

    /**
     * This function adds a new state to the allowed states after this state.
     * @param state $state
     * @return void
     * @throws loopNotAllowedException
     */
    final public function addAllowedStateTransition(state $state): void
    {
        if($state == $this->allowedStatesAfter && !$this->canLoop){
            throw new loopNotAllowedException();
        }
        $this->allowedStatesAfter[] = $state;
    }

    /**
     * @return void Starts the state from the beginning.
     * @throws loopNotAllowedException
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
            throw new loopNotAllowedException();
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