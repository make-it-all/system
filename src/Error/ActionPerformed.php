<?php namespace Application\Error;

/*
    ActionPerformed exception handler, thrown when an action attempts to enact a
    previous action that should not be replicated.
    @contributers Chris Head
*/

class ActionPerformed extends \Exception {}
