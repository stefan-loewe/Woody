<?php

namespace Woody\Event;



class EventFactory {
    private static $previousFocusedWindow = null;
    private static $previousFocusedControl = null;

    public static function createEvent($windowID, $id, $controlID, $typeID, $propertyID) {
        $source = \Woody\Components\Component::getControlByID($controlID);

        // window close button does not have a control, so create a window close event
        if(self::isWindowCloseEvent($id)) {
            echo PHP_EOL.PHP_EOL.'create window close event';
            $source->destroy();
        }
        else if(self::isMouseEvent($typeID)) {
            echo PHP_EOL.PHP_EOL.'create new mouse event';
            $e = new MouseEvent($windowID, $id, $controlID, $typeID, $propertyID);
            echo $e;
        }
        else if(self::isKeyEvent($typeID)) {
            echo PHP_EOL.PHP_EOL.'create new key down event';
            $e = new KeyEvent($windowID, $id, $controlID, $typeID, $propertyID);
            echo $e;
        }
        else if(self::isFocusEvent($typeID)) {
            if(is_a($source, '\Woody\Components\Windows\AbstractWindow')) {
                if(self::$previousFocusedWindow != null)
                    echo PHP_EOL.PHP_EOL.'create new kill_focus event for window';

                echo PHP_EOL.PHP_EOL.'create new focus event for window';
                self::$previousFocusedWindow = $source;
            }
            else if(is_a($source, '\Woody\Components\Controls\Control')) {
                if(self::$previousFocusedControl != null)
                    echo PHP_EOL.PHP_EOL.'create new kill_focus event for control';
                echo PHP_EOL.PHP_EOL.'create new focus event for control';
                self::$previousFocusedControl = $source;
            }
        }

        if(isset($source->actionListeners) && $source->actionListeners != null)
            $source->actionListeners[0]->actionPerformed();
    }

    private static function isWindowCloseEvent($id) {
        return $id === IDCLOSE;
    }

    private static function isMouseEvent($typeID) {
        return ($typeID & WBC_MOUSEDOWN) || ($typeID & WBC_MOUSEUP) || ($typeID === WBC_DBLCLICK);
    }

    private static function isKeyEvent($typeID) {
        return ($typeID === WBC_KEYDOWN) || ($typeID === WBC_KEYUP);
    }

    private static function isFocusEvent($typeID) {
        return $typeID === WBC_GETFOCUS;
    }
}