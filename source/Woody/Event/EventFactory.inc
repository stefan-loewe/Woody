<?php

namespace Woody\Event;

use \Utils\DataStructures\RingBuffer;
use \Utils\Geom\Dimension;
use \Utils\Http\HttpGetRequest;
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
  public static function createEvent(EventInfo $eventInfo) {
    if(self::$eventBuffer === null) {
      self::$eventBuffer = new RingBuffer(10);
    }

    // window close button is not a real control, so handle it here - close window
    if($eventInfo->isWindowCloseEvent()) {
      //$eventInfo->source->destroy();
      self::createAndDispatchWindowClosedEvent($eventInfo);
      return;
    }

    // timeout of timers are handled here, too - the callback is executed by calling Timer::run
    else if($eventInfo->isTimerEvent()) {
      Timer::getTimerByID($eventInfo->id)->run();
      return;
    }

    else if($eventInfo->isFocusEvent()) {
      self::createAndDispatchFocusEvent($eventInfo);
    }

    else if($eventInfo->isMouseEvent()) {
      self::createAndDispatchMouseEvent($eventInfo);
    }

    else if($eventInfo->isKeyEvent()) {
      self::createAndDispatchKeyEvent($eventInfo);
    }

    if($eventInfo->isControlEvent()) {
      // only handle non-focus-events of controls (FocusEvent was handled above and does not trigger Actions)
      if(!$eventInfo->isFocusEvent()) {
        self::createAndDispatchActionEvent($eventInfo);
      }

      return;
    }

    // create and dispatch WindowEvents, e.g. when resizing a window
    if($eventInfo->isWindowEvent()) {
      if($eventInfo->isWindowResizeEvent()) {
        self::createAndDispatchWindowResizeEvent($eventInfo);
      }
    }

    // app crashes when an ActionEvent was created and $event was returned here !?!?!?!
    //return $event;
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
    $event = new FocusEvent($eventInfo);

    self::$previousFocusedControl = $eventInfo->source;

    foreach($event->getSource()->getFocusListeners() as $focusListener) {
      $focusListener->focusGained($event);
    }
  }

  private static function createAndDispatchMouseEvent($eventInfo) {
      $event = new MouseEvent($eventInfo);

      if($event->isMouseDownEvent()) {
        self::$eventBuffer->add($event);
      }

      foreach($event->getSource()->getMouseListeners() as $mouseListener) {
        if($event->isMouseDownEvent()) {
          $mouseListener->mousePressed($event);
        }
        else if($event->isMouseUpEvent()) {
          $mouseListener->mouseReleased($event);
        }
      }
  }

  private static function createAndDispatchKeyEvent($eventInfo) {
      $event = new KeyEvent($eventInfo);

      foreach($event->getSource()->getKeyListeners() as $keyListener) {
        if($event->isKeyDownEvent()) {
          $keyListener->keyPressed($event);
        }

        else if($event->isKeyUpEvent()) {
          $keyListener->keyReleased($event);
        }
      }
  }

  // no other events are raised when, e.g., clicking on a button, or selecting from a listbox, therefore, AcionEvents
  // are needed
  // TODO: for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent
  private static function createAndDispatchActionEvent($eventInfo) {
    // trigger action events for all Actionables, as well as when the propertyID actually is a HttpGetRequest
    // the later are special events, namely those raised by HTMLControls when they receive new data
    // @see HtmlControlServer::processClient
    if($eventInfo->source instanceof Actionable || $eventInfo->property instanceof HttpGetRequest) {
      $actionEvent = new ActionEvent($eventInfo);

      foreach($event->getSource()->getActionListeners() as $actionListener) {
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

    $event = new WindowResizeEvent($eventInfo, $oldDim, $newDim);

    // update the dimension of the Woody object here
    $eventInfo->source->resizeTo($newDim);

    foreach($event->getSource()->getWindowResizeListeners() as $resizeListener) {
      $resizeListener->windowResized($event);
    }
  }

  private static function createAndDispatchWindowClosedEvent($eventInfo) {
    $event = new WindowCloseEvent($eventInfo);

    $event->getSource()->getWindowCloseListener()->windowClosed($event);
  }
}