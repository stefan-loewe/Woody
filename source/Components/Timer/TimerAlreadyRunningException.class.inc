<?php

namespace Woody\Components\Timer;

class TimerAlreadyRunningException extends \BadMethodCallException
{
    /**
     * This method acts as the constructor of the class.
     *
     * @param \Woody\Components\Timer\Timer $timer the timer that is in an illegal state
     */
    public function __construct(Timer $timer)
    {
        parent::__construct('The timer with the id '.$timer->getID().' is already running');
    }
}