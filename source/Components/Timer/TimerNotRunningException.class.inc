<?php

namespace Woody\Components\Timer;

class TimerNotRunningException extends \RuntimeException
{
    public function __construct(Timer $timer)
    {
        parent::__construct('The timer with the id '.$timer->getID().' is not running');
    }
}