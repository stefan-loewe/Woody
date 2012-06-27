<?php

namespace Woody\Event;

/**
 * @TODO this is a mess
 */
class EventFactory {
  /**
   * the window that just had the focus before
   *
   * @var \Woody\Components\Windows\AbstractWindow
   */
  private static $previousFocusedWindow = null;

  /**
   * the control that just had the focus before
   *
   * @var \Woody\Components\Controls\Control
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

    $source = \Woody\Components\Component::getComponentByID($controlID);

    // window close button does not have a control, so create a window close event
    if(self::isWindowCloseEvent($id)) {
      $source->destroy();
      $event = 'WindowCloseEvent';
    }
    else if(self::isTimerEvent($windowID, $controlID, $id)) {
      \Woody\Components\Timer\Timer::getTimerByID($id)->run();
      $event = 'TimerEvent';
    }
    else if(self::isFocusEvent($typeID)) {
      $event = new FocusEvent($windowID, $id, $controlID, $typeID, $propertyID, self::$previousFocusedControl);
      self::$previousFocusedControl = $source;

      foreach($source->getFocusListeners() as $focusListener) {
        $focusListener->focusGained($event);
      }
    }
    else if(self::isMouseEvent($typeID)) {
      $event = new MouseEvent($windowID, $id, $controlID, $typeID, $propertyID);

      foreach($source->getMouseListeners() as $mouseListener) {
        if(($typeID & WBC_MOUSEDOWN) === WBC_MOUSEDOWN) {
          if($event->getPressedButton() !== MouseEvent::NO_BUTTON) {
            self::$eventBuffer->add($event);
          }
          $mouseListener->mousePressed($event);
        }
        else {
          $mouseListener->mouseReleased($event);
        }
      }
    }
    else if(self::isKeyEvent($typeID)) {
      $event = new KeyEvent($windowID, $id, $controlID, $typeID, $propertyID);

      foreach($source->getKeyListeners() as $keyListener) {
        ($typeID === WBC_KEYDOWN) ? $keyListener->keyPressed($event) : $keyListener->keyReleased($event);
      }
    }

    // for EditBox, an ActionEvent would be FocusLost + content changed = onChangeEvent

    // additionally, fire a ActionEvent if appropriate
    if(self::isControlEvent($id)) {
      $actionEvent = null;

      // handled before
      if(!self::isFocusEvent($typeID)) {

        if(/* self::isKeyUpEvent($typeID) && */$source instanceof \Woody\Components\Controls\PushButton) {
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
  }

  private static function isWindowCloseEvent($id) {
    return $id === IDCLOSE;
  }

  private static function isMouseEvent($typeID) {
    return is_int($typeID)
            && (($typeID & WBC_MOUSEDOWN) || ($typeID & WBC_MOUSEUP) || ($typeID === WBC_DBLCLICK));
  }

  private static function isKeyEvent($typeID) {
    return is_int($typeID)
            && (($typeID === WBC_KEYDOWN) || ($typeID === WBC_KEYUP));
  }

  private static function isKeyUpEvent($typeID) {
    return is_int($typeID) && ($typeID === self::$KEY_UP_EVENT);
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

    if(self::$eventBuffer === null) {
      self::$eventBuffer = new RingBuffer(10);
    }

    $libUser  = wb_load_library('USER');
    $function = wb_get_function_address('GetDoubleClickTime', $libUser);
    $interval = wb_call_function($function, array()) / 1000;

    $button = $event->getPressedButton();
    $clickCount = 1;

    foreach(self::$eventBuffer->getReveresedQueue() as $index => $queuedEvent) {
      //var_dump($queuedEvent);
      if($event == $queuedEvent)
        continue;

      if($queuedEvent === null)
        return $clickCount;

      if($queuedEvent->getPressedButton() !== $button)
        return $clickCount;

      if(($event->time - $queuedEvent->time) > $interval) {
        return $clickCount;
      }

      $clickCount++;
    }

    return $clickCount;
  }
}

class RingBuffer {
  private $buffer = null;

  private $index  = 0;

  public function __construct($size) {
    $this->buffer = new \SplFixedArray($size);
  }

  public function add($item) {
    $this->index = (++$this->index) % $this->buffer->getSize();

    //var_dump('adding to index = '.$this->index);

    $this->buffer[$this->index] = $item;
  }

  public function getReveresedQueue() {
    $result = array();
    for($i = 0; $i < $this->buffer->getSize(); $i++) {
      $currentIndex = $this->index - $i;

      if($currentIndex < 0) {
        $currentIndex = $currentIndex + $this->buffer->getSize();
      }

      if($this->buffer[$currentIndex] !== null) {
        $result[] = $this->buffer[$currentIndex];
      }
    }

    return $result;
  }
}