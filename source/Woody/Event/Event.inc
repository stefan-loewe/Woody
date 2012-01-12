<?php

namespace Woody\Event;

class Event {
    /**
     * the winbinder identifier for the window or origin
     *
     * @var int
     */
    private $windowID   = null;

    /**
     * the identifier for the control or origin
     *
     * @var int
     */
    private $id         = null;

    /**
     * the winbinder identifier for the control or origin
     *
     * @var int
     */
    private $controlID  = null;

    /**
     * the winbinder identifier for the type of event
     *
     * @var int
     */
    private $type       = null;

    /**
     * the winbinder identifier for properties of the event
     *
     * @var int
     */
    private $properties = null;

    /**
     * the timestamp of the event
     *
     * @var int
     */
    private $time       = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param int $windowID the winbinder identifier for the control or origin
     * @param int $id the identifier for the control or origin
     * @param int $controlID the winbinder identifier for the control or origin
     * @param int $type the winbinder identifier for the type of event
     * @param int $properties the winbinder identifier for properties of the event
     */
    public function __construct($windowID, $id, $controlID, $type, $properties) {
        $this->windowID     = $windowID;
        $this->id           = $id;
        $this->controlID    = $controlID;
        $this->type         = $type;
        $this->properties   = $properties;
        $this->time         = microtime(TRUE);
    }

    public function __get($memberName) {
        return $this->$memberName;
    }

    /**
     * This method returns the string representation of the event.
     *
     * @return string the string representation of the event
     */
    public function __toString() {
        return 'windowID = '.$this->windowID.PHP_EOL.
            'id = '.$this->id.PHP_EOL.
            'controlID = '.$this->controlID.PHP_EOL.
            'param1 = '.$this->type.PHP_EOL.
            'param2 = '.$this->properties;
    }
}