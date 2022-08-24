<?php

namespace sssm;

use Exception;

final class State
{
    /**
     * @param bool $canLoop Sets if looping the state is allowed
     * @throws LoopNotAllowedException
     */
    public function __construct(private string $stateName = "Undefined state name", private bool $canLoop = true){
        if($this->canLoop){
            $this->allowedStatesAfter[] = $this;
        }elseif(in_array($this, $this->allowedStatesAfter)){
            throw new LoopNotAllowedException($this);
        }
    }

    public function getStateName(): string {
        return $this->stateName;
    }

    public function getCanLoop(): bool {
        return $this->canLoop;
    }

    /**
    * @var array An array of states that are allowed to be looped after this state.
     */
    private array $allowedStatesAfter = [];

    public function getAllowedStateTransitions(): array {
        return $this->allowedStatesAfter;
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

    /**
     * This function adds a new state to the allowed states after this state.
     * @param State $state
     * @return void
     * @throws LoopNotAllowedException
     */
    public function addAllowedStateTransition(State $state): void
    {
        if($state == $this->allowedStatesAfter && !$this->canLoop){
            throw new LoopNotAllowedException($this);
        }
        $this->allowedStatesAfter[] = $state;
    }
}