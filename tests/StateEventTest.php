<?php

namespace sssm\tests;

include_once __DIR__."/../State.php";
include_once __DIR__."/../StateMachine.php";
include_once __DIR__."/../StateTransitionNotAllowedException.php";
include_once __DIR__."/../LoopNotAllowedException.php";

use PHPUnit\Framework\TestCase;
use sssm\StateTransitionNotAllowedException;
use sssm\LoopNotAllowedException;
use sssm\State as State;
use sssm\StateMachine;

class StateEventTest extends TestCase
{
    function generateTestStates(): array
    {
        $state1 = new State('State 1');
        $state2 = new State('State 2');
        $state3 = new State('State 3', false);
        return array($state1, $state2, $state3);
    }

    function generateTestMachine(array $states): StateMachine
    {
        list($state1, $state2, $state3) = $states;
        $state1->addAllowedStateTransition($state2);
        $state2->addAllowedStateTransition($state3);
        return new StateMachine($state1, $state2, $state3);
    }

    function testOnStateEntered()
    {
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $state1->onStateEnter[] = function(state $state) {
            echo $state->getStateName()." entered";
        };
        $stateMachine->switchState($state1);
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $this->expectOutputString("State 1 entered");
    }

    function testOnStateLeave()
    {
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $state1->onStateLeave[] = function(state $state) {
            echo $state->getStateName()." leaved";
        };
        $stateMachine->switchState($state1);
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $this->expectOutputString("State 1 leaved");
    }

    function testOnLoop()
    {
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $state1->onLoop[] = function(state $state) {
            echo $state->getStateName()." looped";
        };
        $stateMachine->switchState($state1);
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $this->expectOutputString("State 1 looped");
    }
}
