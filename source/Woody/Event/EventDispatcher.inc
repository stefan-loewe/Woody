<?php

namespace Woody\Event;

use \Utils\Http\HttpGetRequest;
use \Woody\Components\Component;
use \Woody\Components\Controls\Actionable;

/**
 * This class is responsible for dispatching the events created by the event factory to listeneres registered at the
 * respective source of the event.
 */
class EventDispatcher {
  public static function dispatchEvent(EventInfo $eventInfo, Event $event) {
    // window close button is not a real control, so handle it here - close window
    var_dump("the event can dispatch itself - it knows its source and type, and onlyhas to implement event::dispatch()");
    var_dump("action event to piggy-back actual event, or other way round");
    if($eventInfo->isWindowCloseEvent()) {
      return self::dispatchWindowClosedEvent($event);
    }

    else if($eventInfo->isFocusEvent()) {
      return self::dispatchFocusEvent($event);
    }

    else if($eventInfo->isMouseEvent()) {
      return self::dispatchMouseEvent($event);
    }

    else if($eventInfo->isKeyEvent()) {
      return self::dispatchKeyEvent($event);
    }
/*
    if($eventInfo->isControlEvent()) {
      // only handle non-focus-events of controls (FocusEvent was handled above and does not trigger Actions)
      if(!$eventInfo->isFocusEvent()) {
        return self::createAndDispatchActionEvent($eventInfo);
      }

      return;
    }
*/
    // create and dispatch WindowEvents, e.g. when resizing a window
    else if($eventInfo->isWindowEvent()) {
      if($eventInfo->isWindowResizeEvent()) {
        return self::createAndDispatchWindowResizeEvent($event);
      }
    }

    // app crashes when an ActionEvent was created and $event was returned here !?!?!?!
    //return $event;
  }

  private static function dispatchFocusEvent($event) {
    foreach($event->getSource()->getFocusListeners() as $focusListener) {
      $focusListener->focusGained($event);
    }
  }

  private static function dispatchMouseEvent($event) {
    foreach($event->getSource()->getMouseListeners() as $mouseListener) {
      if($event->isMouseDownEvent()) {
        $mouseListener->mousePressed($event);
      }
      else if($event->isMouseUpEvent()) {
        $mouseListener->mouseReleased($event);
      }
    }
  }

  private static function dispatchKeyEvent($event) {
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
    $source = Component::getComponentByID($eventInfo->controlID);
    if($source instanceof Actionable || $eventInfo->property instanceof HttpGetRequest) {
      $event = new ActionEvent($eventInfo);

      foreach($event->getSource()->getActionListeners() as $actionListener) {
        $actionListener->actionPerformed($event);
      }
    }
  }

  private static function createAndDispatchWindowResizeEvent($event) {
    foreach($event->getSource()->getWindowResizeListeners() as $resizeListener) {
      $resizeListener->windowResized($event);
    }
  }

  private static function dispatchWindowClosedEvent($event) {
    $event->getSource()->close();
  }
}