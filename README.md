# SSSM - Super simple state machine for PHP

## Report issues

Please open a new issue to report a bug or request a new feature.

## Installation

```bash
composer require sssm/sssm
```

## Usage

### 1. Include SSSM

```php
// 1. Include SSSM
include_once "vendor\sssm\sssm\index.php";

use sssm\state;
use sssm\stateMachine;
```
### 2. Create the states

Syntax:
```php
$stateName = new state(["stateName of allowed transition 1", "stateName of allowed transition 2", ...], canLoop);
```

Example:
```php
// State 1 allows transition to state 2 (can loop(default))
$state1 = new state(["state2"]);
// State 2 allows transition to state 3 and state 1 (loop not allowed=
$state2 = new state(["state3","state1"], false);
// State 3 allows no transition (can loop)
$state3 = new state([]);
```

### 3. Create the state machine

Syntax:
```php
$stateMachine = new stateMachine($initialState);
```

Example:
```php
$stateMachine = new stateMachine($state1);
```

### 4. Add state events (optional)

Syntax:
```php
$stateName->onStateEnter[] = function(){
    // Things that get executed when entering the state
};

$stateName->onLoop[]= function(){
    // Things that get executed when the state gets looped
};

$stateName->onStateLeave[]= function(){
    // Things that get executed when leaving the state
};
```

Example:
```php
$state1->onStateEnter[] = function(){
    echo "State 1 entered\n";
};

$state1->onLoop[]= function(){
    echo "State 1 looped\n";
};

$state1->onStateLeave[]= function(){
    echo "State 1 leaved\n";
};

$state2->onStateEnter[] = function(){
    echo "State 2 entered\n";
};
```

### 5. Loop state

Syntax:
```php
$stateName->loop();
```

Example:
```php
$state1->loop();
```

### 6. Switch state

Syntax:
```php
$stateMachine->switchState($newStateName);
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

include_once "vendor\sssm\sssm\index.php";

use sssm\state;
use sssm\stateMachine;

$state1 = new state(["state2"]);
$state2 = new state(["state3"]);
$state3 = new state([]);

$stateMachine = new stateMachine($state1);

$state1->onStateEnter[] = function(){
    echo "State 1 entered\n";
};

$state1->onLoop[]= function(){
    echo "State 1 looped\n";
};

$state1->onStateLeave[]= function(){
    echo "State 1 leaved\n";
};

$state2->onStateEnter[] = function(){
    echo "State 2 entered\n";
};


$state1->loop();

$stateMachine->switchState($state2);
$stateMachine->switchState($state3);

echo $stateMachine->getCurrentState() === $state3?"State 3 is current state":"State 3 is not current state";

```