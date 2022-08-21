<?php

namespace sssm;

use Exception;

final class state
{
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