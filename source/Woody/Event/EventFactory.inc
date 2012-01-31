<?php

namespace Woody\Event;



class EventFactory {
    private static $previousFocusedWindow = null;
    private static $previousFocusedControl = null;

    public static function createEvent($windowID, $id, $controlID, $typeID, $propertyID) {
        $source = \Woody\Components\Component::getComponentByID($controlID);

        // window close button does not have a control, so create a window close event
        if(self::isWindowCloseEvent($id)) {
            $source->destroy();
        }
        else if(self::isMouseEvent($typeID)) {
            $event = new MouseEvent($windowID, $id, $controlID, $typeID, $propertyID);
        }
        else if(self::isKeyEvent($typeID)) {
            $event = new KeyEvent($windowID, $id, $controlID, $typeID, $propertyID);
        }
        else if(self::isFocusEvent($typeID)) {
            if(is_a($source, '\Woody\Components\Windows\AbstractWindow')) {
                if(self::$previousFocusedWindow != null)
                    echo PHP_EOL.PHP_EOL.'create new kill_focus event for window';

                echo PHP_EOL.PHP_EOL.'create new focus event for window';
                self::$previousFocusedWindow = $source;
            }
            else if(is_a($source, '\Woody\Components\Controls\Control')) {
                $event = new FocusEvent($windowID, $id, $controlID, $typeID, $propertyID, self::$previousFocusedControl);
                self::$previousFocusedControl = $source;
            }
        }

        if(self::isKeyEvent($typeID)) {
            foreach($source->getActionListeners() as $actionListener) {
                $actionListener->actionPerformed($event);
            }
        }
        if(self::isFocusEvent($typeID)) {
            foreach($source->getFocusListeners() as $focusListener) {
                $focusListener->focusGained($event);
            }
        }
        if(self::isKeyEvent($typeID)) {
            foreach($source->getKeyListeners() as $keyListener) {
                ($typeID === WBC_KEYDOWN)
                    ? $keyListener->keyPressed($event)
                    : $keyListener->keyReleased($event);
            }

        }

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