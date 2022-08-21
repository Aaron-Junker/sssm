# 2.0.0
* Renamed composer package to `aaronjunker/sssm` (#1)
* Added composer autoloader.
* Moved different classes in different files.
* Changed syntax of state class from `state(["allowedState1", "allowedState2"], $canLoop)` to `state($stateName, $canLoop)`, which lets you provide the actual class instead of just a string.
* Added new exception: `loopNotAllowedException`.
* Renamed `stateNotAllowedException` to `stateTransitionNotAllowedException`.
* Changed exception string of `stateTransitionNotAllowedException`.
* Added new function to state class: `addAllowedStateTransition()`
* Removed some leftover debug functions.
* You can now add names to states.
  * New function : "$stateMachine->getCurrentStateName()"
* A state object  now gets passed to the event callbacks.
* Some little other changes here and there. (#2)

# 1.0.0
Initial release.