# 2.0.0
* Renamed composer package to `aaronjunker/sssm`
* Added composer autoloader.
* Moved different classes in different files.
* Changed syntax of state class from `state(["allowedState1", "allowedState2"])` to `state([$allowedState1, $allowedState2]`, which lets you provide the actual class instead of just a string.
* Added new exception: `loopNotAllowedException`.
* Changed exception string of `stateNotAllowedException`.
* Removed some leftover debug functions.
* Some little other changes here and there.

# 1.0.0
Initial release.