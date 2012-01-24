<?php

namespace Woody\Event;

use \Utils\Geom\Point;

class MouseEvent extends Event {

    const NO_BUTTON = 0;
    const BUTTON1   = WBC_LBUTTON;
    const BUTTON2   = WBC_RBUTTON;
    const BUTTON3   = WBC_MBUTTON;

    /**
     * This method acts as the constructor of the class.
     *
     * @param int $windowID the winbinder identifier for the control or origin
     * @param int $id the identifier for the control or origin
     * @param int $controlID the winbinder identifier for the control or origin
     * @param int $type the winbinder identifier for the type of event
     * @param int $property the winbinder identifier for properties of the event
     */
    public function __construct($windowID, $id, $controlID, $type, $property) {
        parent::__construct($windowID, $id, $controlID, $type, $property);
    }

    /**
     * This method returns the position of the mouse when the event occured.
     *
     * @var Point the position of the mouse when the event occured
     */
    public function getPosition() {
        return new Point($this->property & 0xFFFF, ($this->property & 0xFFFF0000) >> 16);
    }

    /**
     * This method returns which mouse button was pressed.
     *
     * @return int the mouse button which was pressed, either one of MouseEvent:NO_BUTTON, MouseEvent::BUTTON1, MouseEvent::BUTTON2, MouseEvent::BUTTON3
     */
    public function getPressedButton() {
        $button = self::NO_BUTTON;

        if($this->type & self::BUTTON1)
            $button = self::BUTTON1;

        else if($this->type & self::BUTTON2)
            $button = self::BUTTON2;

        else if($this->type & self::BUTTON3)
            $button = self::BUTTON3;

        return $button;
    }

    public function getClickCount() {
        // TODO: get, thru a system call, the maximal duration between two consequtive clicks, so that the 2nd click is still considered a double-click
    }

    /**
     * This method returns the string representation of the event.
     *
     * @return string the string representation of the event
     */
    public function __toString() {
        return parent::__toString().PHP_EOL.
            'button = '.$this->getPressedButton().PHP_EOL.
            'position = '.$this->getPosition().PHP_EOL.
            'A/C/S = '.$this->isAltKeyPressed().'/'.$this->isCtrlKeyPressed().'/'.$this->isShiftKeyPressed();

    }
}