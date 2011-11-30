<?php

namespace Woody\Event;

class Event
{
    private $windowID   = null;
    private $id         = null;
    private $controlID  = null;
    private $param1     = null;
    private $param2     = null;
    private $time       = null;

    public function __construct($windowID, $id, $controlID, $param1, $param2)
    {
        $this->windowID     = $windowID;
        $this->id           = $id;
        $this->controlID    = $controlID;
        $this->param1       = $param1;
        $this->param2       = $param2;
        $this->time         = microtime(TRUE);
    }

    public function __get($memberName)
    {
        return $this->$memberName;
    }

    public function __toString()
    {
        return 'windowID = '.$this->windowID.PHP_EOL.
            'id = '.$this->id.PHP_EOL.
            'controlID = '.$this->controlID.PHP_EOL.
            'param1 = '.$this->param1.PHP_EOL.
            'param2 = '.$this->param2;
    }
}