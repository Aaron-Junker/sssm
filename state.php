<?php

namespace sssm;

use Exception;

final class state
{
    /**
     * @param bool $canLoop Sets if looping the state is allowed
     * @throws loopNotAllowedException
     */
    public function __construct(public string $stateName = "Undefined state name", private bool $canLoop = true){
        if($this->canLoop){
            $this->allowedStatesAfter[] = $this;
        }elseif(in_array($this, $this->allowedStatesAfter)){
            throw new loopNotAllowedException($this);
        }
    }

    public array $allowedStatesAfter = [];

    /**
     * This function adds a new state to the allowed states after this state.
     * @param state $state
     * @return void
     * @throws loopNotAllowedException
     */
    public function addAllowedStateTransition(state $state): void
    {
        if($state == $this->allowedStatesAfter && !$this->canLoop){
            throw new loopNotAllowedException($this);
        }
        $this->allowedStatesAfter[] = $state;
    }

    /**
     * @return void Starts the state from the beginning.
     * @throws loopNotAllowedException
     */
    public function loop(): void {
        if($this->canLoop) {
            foreach ($this->onLoop as $callback) {
                $callback($this);
            }
            foreach ($this->onStateEnter as $callback) {
                $callback($this);
            }
        }else{
            throw new loopNotAllowedException($this);
        }
    }

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