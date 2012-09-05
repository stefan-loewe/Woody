<?php

namespace Woody\Event;

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
    
    $events = new \ArrayObject();

    // window close button is not a real control, so handle it here - close window
    if($eventInfo->isWindowCloseEvent()) {
      $events[] = self::createWindowClosedEvent($eventInfo);
    }

    // timeout of timers are handled here, too - the callback is executed by calling Timer::run
    else if($eventInfo->isTimerEvent()) {
      // @TODO: make this return an event as well
      Timer::getTimerByID($eventInfo->id)->run();
      $events[] = null;
    }

    else if($eventInfo->isFocusEvent()) {
      $events[] = self::createFocusEvent($eventInfo);
    }

    else if($eventInfo->isMouseEvent()) {
      $events[] = self::createMouseEvent($eventInfo);
    }

    else if($eventInfo->isKeyEvent()) {
      $events[] = self::createKeyEvent($eventInfo);
    }

    if($eventInfo->isControlEvent()) {
      // only handle non-focus-events of controls (FocusEvent was handled above and does not trigger Actions)
      if(!$eventInfo->isFocusEvent()) {
        $events[] = self::createActionEvent($eventInfo);
      }
    }

    // create and dispatch WindowEvents, e.g. when resizing a window
    else if($eventInfo->isWindowEvent()) {
      if($eventInfo->isWindowResizeEvent()) {
        $events[] = self::createWindowResizeEvent($eventInfo);
      }
    }

    // app crashes when an ActionEvent was created and $event was returned here !?!?!?!
    return $events;
  }

  private static function createFocusEvent($eventInfo) {
    $event = new FocusEvent($eventInfo, self::$previousFocusedControl);

    self::$previousFocusedControl = $event->getSource();

    return $event;
  }

  private static function createMouseEvent($eventInfo) {
    $event = new MouseEvent($eventInfo);

    return $event;
  }

  private static function createKeyEvent($eventInfo) {
    return new KeyEvent($eventInfo);
  }

  // no other events are raised when, e.g., clicking on a button, or selecting from a listbox, therefore, AcionEvents
  // are needed
  // TODO: for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent
  private static function createActionEvent($eventInfo) {
    // trigger action events for all Actionables, as well as when the propertyID actually is a HttpGetRequest
    // the later are special events, namely those raised by HTMLControls when they receive new data
    // @see HtmlControlServer::processClient
    $source = Component::getComponentByID($eventInfo->controlID);
    if($source instanceof Actionable || $eventInfo->property instanceof HttpGetRequest) {
      $event = new ActionEvent($eventInfo);
      
      return $event;
    }
    
    return null;
  }

  private static function createWindowResizeEvent($eventInfo) {
    $event = new WindowResizeEvent($eventInfo);

    // update the dimension of the Woody object here (the actual component is already resized)
    $event->getSource()->resizeTo($event->getNewDimension());

    return $event;
  }

  private static function createWindowClosedEvent($eventInfo) {
    return new WindowCloseEvent($eventInfo);
  }
}