<?php

namespace sssm\tests;

include_once __DIR__."/../State.php";
include_once __DIR__."/../StateMachine.php";
include_once __DIR__."/../StateTransitionNotAllowedException.php";
include_once __DIR__."/../LoopNotAllowedException.php";

use PHPUnit\Framework\TestCase;
use sssm\StateTransitionNotAllowedException;
use sssm\LoopNotAllowedException;
use sssm\State;
use sssm\StateMachine;

class LoopTest extends TestCase
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

    function testLooping(): void
    {
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $stateMachine->switchState($state1);
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $stateMachine->loop();
        $this->assertEquals($state1, $stateMachine->getCurrentState());
    }

    function testLoopEventOnLoopFunction(){
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $state1->onLoop[] = function() {
            echo "State 1 looped";
        };
        $state1->onStateEnter[] = function() {
            echo "State 1 entered";
        };
        $state1->onStateLeave[] = function() {
            echo "State 1 leaved";
        };
        $this->expectOutputString("State 1 leavedState 1 loopedState 1 entered");
        $stateMachine->loop();
    }

    function testLoopEventOnSwitchingState(){
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $state1->onLoop[] = function() {
            echo "State 1 looped";
        };
        $state1->onStateEnter[] = function() {
            echo "State 1 entered";
        };
        $state1->onStateLeave[] = function() {
            echo "State 1 leaved";
        };
        $this->expectOutputString("State 1 leavedState 1 loopedState 1 entered");
        $stateMachine->switchState($state1);
    }

    function testLoopNotAllowedExceptionOnLoopFunction(){
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $this->expectException(LoopNotAllowedException::class);
        $stateMachine->switchState($state2);
        $stateMachine->switchState($state3);
        $stateMachine->loop();
    }

    function testStateTransitionNotAllowedExceptionOnSwitchingState(){
        $states = $this->generateTestStates();
        list($state1, $state2, $state3) = $states;
        $stateMachine = $this->generateTestMachine($states);

        $this->expectException(StateTransitionNotAllowedException::class);
        $stateMachine->switchState($state2);
        $stateMachine->switchState($state3);
        $stateMachine->switchState($state3);
    }
}
