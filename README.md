# SSSM - Super simple state machine for PHP

## Report issues

Please open a new issue to report a bug or request a new feature.

## Installation

```bash
composer require aaronjunker/sssm
```

View package on [packagist](https://packagist.org/packages/aaronjunker/sssm).

## Usage

### 1. Include SSSM

```php
include_once "vendor/autoload.php";

use sssm\State;
use sssm\StateMachine;
```
### 2. Create the states

Syntax:
```php
$stateName = new state($stateName, $canLoop);
```

Both arguments are optional. But a name is highly recommended.

Example:
```php
// State 1 named "State 1" (can loop(default))
$state1 = new state("State 1");
// State 2 named "State 2" (can loop)
$state2 = new state("State 2");
// State 3 named "State 3" (loop not allowed)
$state3 = new state("State 3", false);

// Allowed transitions from state 1 to state 2 
$state1->addAllowedStateTransition($state2);
// Allowed transitions from state 2 to state 3
$state2->addAllowedStateTransition($state3);
// State 3 is not allowed to transition to any other state
```

### 3. Create the state machine

Syntax:
```php
$stateMachine = new stateMachine($initialState, $otherState, ...);
```

Example:
```php
$stateMachine = new stateMachine($state1, $state2, $state3);
```

### 4. Add state events (optional)

Syntax:
```php
$stateName->onStateEnter[] = function(State $passedState) {
    // Things that get executed when entering the state
};

$stateName->onLoop[] = function(State $passedState){
    // Things that get executed when the state gets looped
};

$stateName->onStateLeave[] = function(State $passedState){
    // Things that get executed when leaving the state
};
```

Example:
```php
$state1->onStateEnter[] = $state1->onStateEnter[] = function($state){
    echo $state->getStateName()." entered\n";
};

$state1->onLoop[] = function(state $state){
    echo $state->getStateName()." looped\n";
};

$state1->onStateLeave[] = function(state $state){
    echo $state->getStateName()." leaved\n";
};
```

### 5. Loop state

Syntax:
```php
$stateMachine->loop();
```

### 6. Switch state

Syntax:
```php
$stateMachine->switchState($newState);
```

Example:
```php
$stateMachine->switchState($state2);
$stateMachine->switchState($state3);
```

### 7. Get current state

Syntax:
```php
// Returns the current state class
$currentState = $stateMachine->getCurrentState();
```

Example:
```php
echo $stateMachine->getCurrentState() === $state3?"State 3 is current state":"State 3 is not current state";
```

## Full example

```php
<?php

include_once "vendor/autoload.php";

use sssm\state;
use sssm\stateMachine;

$state1 = new state("State 1");
$state2 = new state("State 2");
$state3 = new state("State 3",false);

$state1->addAllowedStateTransition($state2);
$state2->addAllowedStateTransition($state3);

$stateMachine = new stateMachine($state1, $state2, $state3);

$state1->onStateEnter[] = $state2->onStateEnter[] = function($state){
    echo $state->getStateName()." entered\n";
};

$state1->onLoop[] = function(state $state){
    echo $state->getStateName()." looped\n";
};

$state1->onStateLeave[] = function(state $state){
    echo $state->getStateName()." leaved\n";
};


$stateMachine->loop();

$stateMachine->switchState($state2);
$stateMachine->switchState($state3);

echo $stateMachine->getCurrentState() === $state3?"State 3 is current state":"State 3 is not current state";
```
