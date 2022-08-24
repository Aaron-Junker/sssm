<?php

namespace sssm\tests;

include_once __DIR__."/../State.php";
include_once __DIR__."/../StateMachine.php";
include_once __DIR__."/../StateTransitionNotAllowedException.php";

use PHPUnit\Framework\TestCase;
use sssm\State;
use sssm\StateMachine;
use sssm\StateTransitionNotAllowedException;

class StateTransitionTest extends TestCase
{
    function testGeneratingStates(): array
    {
        $state1 = new State('state 1');
        $state2 = new State('state 2');
        $state3 = new State('state 3', false);
        $this->assertEquals('state 1', $state1->getStateName());
        $this->assertEquals('state 2', $state2->getStateName());
        $this->assertEquals('state 3', $state3->getStateName());
        $this->assertEquals([$state1], $state1->getAllowedStateTransitions());
        $this->assertEquals([$state2], $state2->getAllowedStateTransitions());
        $this->assertNotContains([$state2], $state2->getAllowedStateTransitions());
        return array($state1, $state2, $state3);
    }

    /**
     * @depends testGeneratingStates
     */
    function testGeneratingStateMachine(array $states): StateMachine
    {
        list($state1, $state2, $state3) = $states;
        $state1->addAllowedStateTransition($state2);
        $state2->addAllowedStateTransition($state3);
        $stateMachine = new StateMachine($state1, $state2, $state3);
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        return $stateMachine;
    }

    /**
     * @depends testGeneratingStates
     * @depends testGeneratingStateMachine
     */
    function testTransition(array $states, StateMachine $stateMachine): void
    {
        list($state1, $state2, $state3) = $states;
        $this->assertEquals($state1, $stateMachine->getCurrentState());
        $this->assertEquals('state 1', $stateMachine->getCurrentStateName());
        $stateMachine->switchState($state2);
        $this->assertEquals($state2, $stateMachine->getCurrentState());
        $this->assertEquals('state 2', $stateMachine->getCurrentStateName());
        $stateMachine->switchState($state3);
        $this->assertEquals($state3, $stateMachine->getCurrentState());
        $this->assertEquals('state 3', $stateMachine->getCurrentStateName());
    }

    /**
     * @depends testGeneratingStates
     * @depends testGeneratingStateMachine
     */
    function testTransitionNotAllowedException(array $states, StateMachine $stateMachine): void
    {
        list($state1, $state2, $state3) = $states;
        $this->expectException(StateTransitionNotAllowedException::class);
        $stateMachine->switchState($state2);
        $stateMachine->switchState($state1);
    }
}

