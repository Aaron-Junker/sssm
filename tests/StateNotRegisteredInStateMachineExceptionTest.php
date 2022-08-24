<?php

namespace sssm\tests;

include_once __DIR__."/../State.php";
include_once __DIR__."/../StateMachine.php";
include_once __DIR__."/../StateTransitionNotAllowedException.php";
include_once __DIR__."/../StateNotRegisteredInStateMachineException.php";

use PHPUnit\Framework\TestCase;
use sssm\StateTransitionNotAllowedException;
use sssm\StateNotRegisteredInStateMachineException;
use sssm\State;
use sssm\StateMachine;

class StateNotRegisteredInStateMachineExceptionTest extends TestCase
{
    function generateTestStates(): array
    {
        $state1 = new State('state 1');
        $state2 = new State('state 2');
        $state3 = new State('state 3', false);
        return array($state1, $state2, $state3);
    }

    function generateTestMachine(array $states): StateMachine
    {
        list($state1, $state2, $state3) = $states;
        $state1->addAllowedStateTransition($state2);
        $state2->addAllowedStateTransition($state3);
        return new StateMachine($state1, $state2, $state3);
    }


    function testStateNotRegisteredInStateMachineException()
    {
        $stateMachine = $this->generateTestMachine($this->generateTestStates());

        $this->expectException(StateNotRegisteredInStateMachineException::class);
        $stateMachine->switchState(new State());
    }
}
