<?php

namespace Woody\Event;

use \Utils\DataStructures\RingBuffer;
use \Utils\Geom\Dimension;
use \Woody\Components\Component;
use \Woody\Components\Controls\Actionable;
use \Woody\Components\Controls\Control;
use \Woody\Components\Timer\Timer;
use \Woody\Components\Windows\AbstractWindow;

/**
 * @TODO this is a mess
 */
class EventFactory {
  /**
   * the window that just had the focus before
   *
   * @var AbstractWindow
   */
  private static $previousFocusedWindow = null;

  /**
   * the control that just had the focus before
   *
   * @var Control
   */
  private static $previousFocusedControl = null;

  /**
   * identifier for a focus event (WBC_GETFOCUS)
   *
   * @var int
   */
  private static $FOCUS_EVENT = 0x00001000;

  /**
   * identifier for a key-up event (WBC_KEYUP)
   *
   * @var int
   */
  private static $KEY_UP_EVENT = 0x00000800;

  private static $eventBuffer = null;

  public static function createEvent($windowID, $id, $controlID, $typeID, $propertyID) {
    if(self::$eventBuffer === null) {
      self::$eventBuffer = new RingBuffer(10);
    }

//if(!self::isTimerEvent($windowID, $controlID, $id))
  //var_dump(time().': eventing ...');

    $source = Component::getComponentByID($controlID);

    // window close button does not have a control, so create a window close event
    if(self::isWindowCloseEvent($id)) {
      $source->destroy();
      $event = 'WindowCloseEvent';
    }

    else if(self::isTimerEvent($windowID, $controlID, $id)) {
      Timer::getTimerByID($id)->run();
      $event = 'TimerEvent';
    }

    else if(self::isFocusEvent($typeID)) {
      //var_dump('isFocusEvent');
      $event = new FocusEvent($windowID, $id, $controlID, $typeID, $propertyID, self::$previousFocusedControl);
      self::$previousFocusedControl = $source;

      foreach($source->getFocusListeners() as $focusListener) {
        $focusListener->focusGained($event);
      }
    }

    else if(self::isMouseEvent($typeID)) {
      //var_dump('isMouseEvent');
      $event = new MouseEvent($windowID, $id, $controlID, $typeID, $propertyID);

      if(self::isMouseDownEvent($typeID)) {
        self::$eventBuffer->add($event);
      }

      foreach($source->getMouseListeners() as $mouseListener) {
        if(self::isMouseDownEvent($typeID)) {
          $mouseListener->mousePressed($event);
        }
        else if(self::isMouseUpEvent($typeID)) {
          $mouseListener->mouseReleased($event);
        }
      }
    }

    else if(self::isKeyEvent($typeID)) {
      //var_dump('isKeyEvent');
      $event = new KeyEvent($windowID, $id, $controlID, $typeID, $propertyID);

      foreach($source->getKeyListeners() as $keyListener) {
        ($typeID === WBC_KEYDOWN) ? $keyListener->keyPressed($event) : $keyListener->keyReleased($event);
      }
    }
    // for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent

    // additionally, fire a ActionEvent if appropriate
    // maybe let the ActionEvent wrap the RawEvent new ActionEvent($event, $meta)
    if(self::isControlEvent($id)) {
      $actionEvent = null;
//var_dump('actionEvent? -> source = '.get_class($source));
      // handled before
      if(!self::isFocusEvent($typeID)) {

        if($source instanceof Actionable) {
          $actionEvent = new ActionEvent($windowID, $id, $controlID, $typeID, $propertyID);
        }
        else if(is_a($propertyID, 'Utils\Http\HttpGetRequest')) {
          $actionEvent = new ActionEvent($windowID, $id, $controlID, $typeID, $propertyID);
        }

        if($actionEvent !== null) {
          foreach($source->getActionListeners() as $actionListener) {
            $actionListener->actionPerformed($actionEvent);
          }
        }
      }
    }

    if(self::isWindowEvent($windowID, $controlID)) {
      //echo 'WINWINWINWINWIN'.'$typeID = '.$typeID.' / '.$propertyID;
      if($typeID === 0x00002000) {
        foreach($source->getWindowResizeListeners() as $resizeListener) {
          $oldDim = $source->getDimension();

          $newDim = wb_get_size($source->getControlID());
          $newDim = new Dimension($newDim[0], $newDim[1]);

          $event = new WindowResizeEvent($windowID, $id, $controlID, $typeID, $propertyID, $oldDim, $newDim);
          $resizeListener->windowResized($event);

          $source->resizeTo($newDim);
        }
      }
    }

    // app crashes when returning event here, and also an ActionEvent was created !??!!?
    //return $event;
  }

  private static function isWindowCloseEvent($id) {
    return $id === IDCLOSE;
  }

  private static function isMouseEvent($typeID) {
    return is_int($typeID) && (($typeID & WBC_MOUSEDOWN) || ($typeID & WBC_MOUSEUP) || ($typeID & WBC_DBLCLICK));
  }

  private static function isMouseDownEvent($typeID) {
    return is_int($typeID) && (($typeID & WBC_MOUSEDOWN) || ($typeID & WBC_DBLCLICK));
  }

  private static function isMouseUpEvent($typeID) {
    return is_int($typeID) && ($typeID & WBC_MOUSEUP);
  }

  private static function isKeyEvent($typeID) {
    return is_int($typeID) && (($typeID === WBC_KEYDOWN) || ($typeID === WBC_KEYUP));
  }

  private static function isFocusEvent($typeID) {
    return is_int($typeID) && ($typeID === self::$FOCUS_EVENT);
  }

  private static function isTimerEvent($windowID, $controlID, $id) {
    return self::isWindowEvent($windowID, $controlID) && $id != 0 && $id != IDCLOSE;
  }

  private static function isWindowEvent($windowID, $controlID) {
    return $windowID == $controlID;
  }

  private static function isControlEvent($id) {
    return $id !== 0;
  }

  public static function getClickCount(MouseEvent $event) {
    $libUser  = wb_load_library('USER');
    $function = wb_get_function_address('GetDoubleClickTime', $libUser);
    $interval = wb_call_function($function, array()) / 1000;

    $button = $event->getPressedButton();
    $clickCount = 0;

    $eventFound = FALSE;
    foreach(self::$eventBuffer->getLiFoOrder() as $currentEvent) {
      $eventFound = $eventFound || $event == $currentEvent;

      if($eventFound) {
        if($currentEvent === null) {
          return $clickCount;
        }

        if($currentEvent->getPressedButton() !== $button) {
          return $clickCount;
        }

        if(($event->time - $currentEvent->time) > $interval) {
          return $clickCount;
        }

        $clickCount++;
      }
    }

    return $clickCount;
  }
}