<?php

namespace ws\loewe\Woody\Event;

use ArrayObject;
use Traversable;
use ws\loewe\Utils\Http\HttpGetRequest;
use ws\loewe\Woody\Components\Controls\Actionable;
use ws\loewe\Woody\Components\Controls\Control;

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
   * This method creates a collection of events based in the event info object that is given as input.
   *
   * @param EventInfo $eventInfo the event info object containing the raw event information
   * @return Traversable
   */
  public static function createEvent(EventInfo $eventInfo) {
    $events = new ArrayObject();

    // window close button is not a real control, so handle it here - close window
    if($eventInfo->isWindowCloseEvent()) {
      $events[] = self::createWindowClosedEvent($eventInfo);
    }

    // events being triggered when pressing ENTER or ESC in controls
    else if($eventInfo->isOkEvent() || $eventInfo->isCancelEvent()) {
      // ignore for now
    }

    // timeout of timers are handled here, too - the callback is executed by calling Timer::run
    else if($eventInfo->isTimerEvent()) {
      $events[] = self::createTimeoutEvent($eventInfo);
    }

    else if($eventInfo->isAcceleratorEvent()) {
      $events[] = self::createActionEvent($eventInfo);
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

    // create and dispatch WindowEvents, e.g. when resizing a window
    else if($eventInfo->isWindowEvent()) {
      if($eventInfo->isWindowResizeEvent()) {
        $events[] = self::createWindowResizeEvent($eventInfo);
      }
    }

    if($eventInfo->isControlEvent() && !$eventInfo->isTimerEvent()) {
      // only handle non-focus-events of controls (FocusEvent was handled above and does not trigger Actions)
      if(!$eventInfo->isFocusEvent()) {
        $events[] = self::createActionEvent($eventInfo);
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

  // TODO: for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent
  private static function createActionEvent($eventInfo) {
    $event = new ActionEvent($eventInfo);

    // only Actionables and HttpGetRequests can trigger action events, the rest just emits NoOpEvents
    if(!(($event->getSource() instanceof Actionable)
      || ($event->property instanceof HttpGetRequest))) {
      return new NoOpEvent($eventInfo);
    }

    return $event;
  }

  private static function createWindowResizeEvent($eventInfo) {
    $event = new WindowResizeEvent($eventInfo);

    // update the dimension of the Woody object here (the actual component is already resized)
    if($event->getSource() !== null) {
      $event->getSource()->resizeTo($event->getNewDimension());
    }

    return $event;
  }

  private static function createWindowClosedEvent($eventInfo) {
    return new WindowCloseEvent($eventInfo);
  }

  private static function createTimeoutEvent($eventInfo) {
    return new TimeoutEvent($eventInfo);
  }
}