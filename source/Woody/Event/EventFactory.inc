<?php

namespace Woody\Event;

use \Utils\DataStructures\RingBuffer;
use \Utils\Geom\Dimension;
use \Utils\Http\HttpGetRequest;
use \Woody\Components\Component;
use \Woody\Components\Controls\Actionable;
use \Woody\Components\Controls\Control;
use \Woody\Components\Timer\Timer;

/**
 * This class is responsible for creating events from the raw winbinder event data. Furhtermore, it dispatched the
 * created events to listeneres registered at the respective source of the event.
 */
class EventFactory {
  /**
   * the control that just had the focus before
   *
   * @var Control
   */
  private static $previousFocusedControl = null;

  /**
   * the event ring buffer - needed for determining the click-counter of mouse click events
   *
   * @var RingBuffer
   */
  private static $eventBuffer = null;

  /**
   * This method creates events and dispatches them to the respective listeners.
   *
   * @param int $windowID the id of the window where the event was triggered
   * @param int $id the internal winbinder id of the source control
   * @param int $controlID the control id of the source control
   * @param int $typeID the type id of the event
   * @param int $propertyID the property id of the event
   * @return void
   */
  public static function createEvent($windowID, $id, $controlID, $typeID, $propertyID) {
    if(self::$eventBuffer === null) {
      self::$eventBuffer = new RingBuffer(10);
    }

    $eventInfo = self::createEventInfo($windowID, $id, $controlID, $typeID, $propertyID);

    // window close button is not a real control, so handle it here - close window
    if(self::isWindowCloseEvent($eventInfo->id)) {
      $eventInfo->source->destroy();
      return;
    }

    // timeout of timers are handled here, too - the callback is executed by calling Timer::run
    else if(self::isTimerEvent($eventInfo->windowID, $eventInfo->controlID, $eventInfo->id)) {
      Timer::getTimerByID($eventInfo->id)->run();
      return;
    }

    else if(self::isFocusEvent($eventInfo->typeID)) {
      self::createAndDispatchFocusEvent($eventInfo);
    }

    else if(self::isMouseEvent($eventInfo->typeID)) {
      self::createAndDispatchMouseEvent($eventInfo);
    }

    else if(self::isKeyEvent($eventInfo->typeID)) {
      self::createAndDispatchKeyEvent($eventInfo);
    }

    if(self::isControlEvent($eventInfo->id)) {
      // only handle non-focus-events of controls (FocusEvent was handled above and does not trigger Actions)
      if(!self::isFocusEvent($eventInfo->typeID)) {
        self::createAndDispatchActionEvent($eventInfo);
      }

      return;
    }

    // create and dispatch WindowEvents, e.g. when resizing a window
    if(self::isWindowEvent($eventInfo->windowID, $eventInfo->controlID)) {
      if(self::isWindowResizeEvent($eventInfo->typeID)) {
        self::createAndDispatchWindowResizeEvent($eventInfo);
      }
    }

    // app crashes when an ActionEvent was created and $event was returned here !?!?!?!
    //return $event;
  }

  private static function createEventInfo($windowID, $id, $controlID, $typeID, $propertyID) {
    $eventInfo = new \stdClass();

    $eventInfo->windowID    = $windowID;
    $eventInfo->id          = $id;
    $eventInfo->controlID   = $controlID;
    $eventInfo->typeID      = $typeID;
    $eventInfo->propertyID  = $propertyID;
    $eventInfo->source      = Component::getComponentByID($controlID);

    return $eventInfo;
  }

  private static function isWindowCloseEvent($id) {
    return $id === IDCLOSE;
  }

  private static function isTimerEvent($windowID, $controlID, $id) {
    return self::isWindowEvent($windowID, $controlID) && $id != 0 && $id != IDCLOSE;
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
    return is_int($typeID) && ($typeID === WBC_GETFOCUS);
  }

  private static function isWindowEvent($windowID, $controlID) {
    return $windowID == $controlID;
  }

  private static function isWindowResizeEvent($typeID) {
    return is_int($typeID) && ($typeID === WBC_RESIZE);
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

  private static function createAndDispatchFocusEvent($eventInfo) {
    $event = new FocusEvent(
      $eventInfo->windowID,
      $eventInfo->id,
      $eventInfo->controlID,
      $eventInfo->typeID,
      $eventInfo->propertyID,
      self::$previousFocusedControl
    );

    self::$previousFocusedControl = $eventInfo->source;

    foreach($eventInfo->source->getFocusListeners() as $focusListener) {
      $focusListener->focusGained($event);
    }
  }

  private static function createAndDispatchMouseEvent($eventInfo) {
      $event = new MouseEvent(
        $eventInfo->windowID,
        $eventInfo->id,
        $eventInfo->controlID,
        $eventInfo->typeID,
        $eventInfo->propertyID
      );

      if(self::isMouseDownEvent($eventInfo->typeID)) {
        self::$eventBuffer->add($event);
      }

      foreach($eventInfo->source->getMouseListeners() as $mouseListener) {
        if(self::isMouseDownEvent($eventInfo->typeID)) {
          $mouseListener->mousePressed($event);
        }
        else if(self::isMouseUpEvent($eventInfo->typeID)) {
          $mouseListener->mouseReleased($event);
        }
      }
  }

  private static function createAndDispatchKeyEvent($eventInfo) {
      $event = new KeyEvent(
        $eventInfo->windowID,
        $eventInfo->id,
        $eventInfo->controlID,
        $eventInfo->typeID,
        $eventInfo->propertyID
      );

      foreach($eventInfo->source->getKeyListeners() as $keyListener) {
        ($eventInfo->typeID === WBC_KEYDOWN) ? $keyListener->keyPressed($event) : $keyListener->keyReleased($event);
      }
  }

  // no other events are raised when, e.g., clicking on a button, or selecting from a listbox, therefore, AcionEvents
  // are needed
  // TODO: for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent
  private static function createAndDispatchActionEvent($eventInfo) {
    // trigger action events for all Actionables, as well as when the propertyID actually is a HttpGetRequest
    // the later are special events, namely those raised by HTMLControls when they receive new data
    // @see HtmlControlServer::processClient
    if($eventInfo->source instanceof Actionable || $eventInfo->propertyID instanceof HttpGetRequest) {
      $actionEvent = new ActionEvent(
        $eventInfo->windowID,
        $eventInfo->id,
        $eventInfo->controlID,
        $eventInfo->typeID,
        $eventInfo->propertyID
      );

      foreach($eventInfo->source->getActionListeners() as $actionListener) {
        $actionListener->actionPerformed($actionEvent);
      }
    }
  }

  private static function createAndDispatchWindowResizeEvent($eventInfo) {
    // the previous dimension is still stored in the Woody object
    $oldDim = $eventInfo->source->getDimension();

    // the winbinder resource already has the new dimension
    $newDim = wb_get_size($eventInfo->source->getControlID());
    $newDim = new Dimension($newDim[0], $newDim[1]);

    $event = new WindowResizeEvent(
      $eventInfo->windowID,
      $eventInfo->id,
      $eventInfo->controlID,
      $eventInfo->typeID,
      $eventInfo->propertyID,
      $oldDim,
      $newDim
    );

    // update the dimension of the Woody object here
    $eventInfo->source->resizeTo($newDim);

    foreach($eventInfo->source->getWindowResizeListeners() as $resizeListener) {
      $resizeListener->windowResized($event);
    }
  }
}