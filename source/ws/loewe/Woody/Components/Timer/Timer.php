<?php

namespace ws\loewe\Woody\Components\Timer;

use \ws\loewe\Woody\WinBinderException;
use \ws\loewe\Woody\Components\Component;
use \ws\loewe\Woody\Components\Windows\AbstractWindow;
use \ws\loewe\Woody\Event\TimeoutListener;
use \ws\loewe\Woody\Event\TimeoutAdapter;

class Timer {
  /**
   * the id of the timer
   *
   * @var int
   */
  protected $id = null;

  /**
   * the window to which the timer is bound to
   *
   * @var \ws\loewe\Woody\Components\Windows\AbstractWindow
   */
  protected $window = null;

  /**
   * the number of milliseconds that pass between timeout
   *
   * @var int
   */
  protected $interval = null;

  /**
   * boolean flag to determine, if timer is running
   *
   * @var boolean
   */
  private $isRunning = false;

  /**
   * counter for counting how often the timer was already called
   *
   * @var int
   */
  protected $counter = 0;

  /**
   * the collection of timeout listeners
   *
   * @var \SplObjectStorage
   */
  private $timeoutListeners = null;

  /**
   * the collection of all timers
   *
   * @var array[int]Timer
   */
  private static $timers = array();

  /**
   * timeout used for scheduling unit tests
   */
  const TEST_TIMEOUT = 100;

  /**
   * This method acts as the constructor of the timer.
   *
   * @param \Closure $callback the default callback that is executed on each timeout
   * @param \ws\loewe\Woody\Components\Windows\AbstractWindow $window the window to which the timer is bound to.
   * @param int $interval the number of milliseconds that pass between each timeout
   */
  public function __construct(\Closure $callback, AbstractWindow $window, $interval) {
    $this->id = Component::getUniqueID();

    $this->window   = $window;

    $this->interval = $interval;

    // an auxilliary callback to track the number of executions
    $this->addTimeoutListener(new TimeoutAdapter(function () {$this->counter++;}));

    // the default callback provided by the user
    $this->addTimeoutListener(new TimeoutAdapter($callback));

    self::$timers[$this->id] = $this;
  }

  /**
   * This method return the id of the timer.
   *
   * @return int
   */
  public function getID() {
    return $this->id;
  }

  /**
   * This method returns the timer associated with the given woody id.
   *
   * @param int $timerID the woody id of the timer
   * @return Timer the timer with the given id, or null if no timer with this id exists
   */
  public static function getTimerByID($timerID) {
    if(isset(self::$timers[$timerID])) {
      return self::$timers[$timerID];
    }

    return null;
  }

  /**
   * This method starts the timer. If it was already started before without being destroyed since, an exception is
   * thrown.
   *
   * @return ws\loewe\Woody\Components\Timer\Timer $this
   */
  public function start() {
    if($this->isRunning) {
      throw new TimerAlreadyRunningException($this);
    }

    if(!wb_create_timer($this->window->getControlID(), $this->id, $this->interval)) {
      throw new WinBinderException('Unable to create winbinder timer object for timer with id '.$this->id);
    }

    $this->isRunning = true;

    return $this;
  }

  /**
   * This method destroys the timer, i.e. associated callbacks will no longer be executed.
   *
   * @return ws\loewe\Woody\Components\Timer\Timer $this
   */
  public function destroy() {
    if(!$this->isRunning) {
      throw new TimerNotRunningException($this);
    }

    $this->isRunning = false;

    if(!wb_destroy_timer($this->window->getControlID(), $this->id)) {
      throw new WinBinderException('Unable to destroy winbinder timer object for timer with id '.$this->id);
    }

    return $this;
  }

  /**
   * This method determines if the timer is running or not.
   *
   * @return true if the the timer is running, else false
   */
  public function isRunning() {
    return $this->isRunning;
  }

  /**
   * This method determines how often the timer was called.
   *
   * @return int the number of times the counter was called
   */
  public function getExecutionCount() {
    return $this->counter;
  }

  /**
   * This method adds a timeout listener to this timer.
   *
   * @param TimeoutListener $timeoutListener the timeout listener to add
   * @return Timer $this
   */
  public function addTimeoutListener(TimeoutListener $timeoutListener) {
    if($this->timeoutListeners == null) {
      $this->timeoutListeners = new \SplObjectStorage();
    }

    $this->timeoutListeners->attach($timeoutListener);

    return $this;
  }

  /**
   * This method returns the collection of timeout listeners registered for this timer.
   *
   * @return \SplObjectStorage the collection of timeout listeners registered for this timer
   */
  public function getTimeoutListeners() {
    return ($this->timeoutListeners == null) ? new \SplObjectStorage() : $this->timeoutListeners;
  }

  /**
   * This method removes a timeout listener from this timer.
   *
   * @param TimeoutListener $timeoutListener the timeout listener to remove
   * @return Timer $this
   */
  public function removeTimeoutListener(TimeoutListener $timeoutListener) {
    if($this->timeoutListeners != null) {
      $this->timeoutListeners->detach($timeoutListener);
    }

    return $this;
  }
}