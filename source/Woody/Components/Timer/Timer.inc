<?php

namespace Woody\Components\Timer;

use \Woody\WinBinderException;
use \Woody\Components\Component;
use \Woody\Components\Windows\AbstractWindow;
use \Woody\Event\EventHandler;

class Timer {
    /**
     * the id of the timer
     *
     * @var int
     */
    protected $id           = null;

    /**
     * the window to which the timer is bound to
     *
     * @var \Woody\Components\Windows\AbstractWindow
     */
    protected $window       = null;

    /**
     * the callback that is executed once the timer is activated
     *
     * @var \Closure
     */
    protected $callback     = null;

    /**
     * the number of milliseconds that pass between each call to the callback of the timer
     *
     * @var int
     */
    protected $interval     = null;

    /**
     * boolean flag to determine, if timer is running
     *
     * @var boolean
     */
    private $isRunning      = false;

    private static $timers  = array();

    /**
     * timeout used for scheduling callbacks for unit tests
     */
    const TEST_TIMEOUT      = 100;

    /**
     * This method acts as the constructor of the timer.
     *
     * @param \Closure $callback the callback that is executed once the timer is activated
     * @param \Woody\Components\Windows\AbstractWindow $window the window to which the timer is bound to.
     * @param int $interval the number of milliseconds that pass between each call to the callback of the timer
     */
    public function __construct(\Closure $callback, AbstractWindow $window, $interval) {
        $this->id       = Component::getUniqueID();

        $this->callback = $callback;

        $this->window   = $window;

        $this->interval = $interval;

        self::$timers[$this->id] = $this;
    }

    public static function getTimerByID($timerID) {
        return self::$timers[$timerID];
    }

    /**
     * This method starts the timer. If it was already started before without being destroyed since, an exception is thrown.
     *
     * @return Woody\Components\Timer\Timer $this
     */
    public function start() {
        if($this->isRunning)
            throw new TimerAlreadyRunningException($this);

        if(!wb_create_timer($this->window->getControlID(), $this->id, $this->interval))
            throw new WinBinderException('Unable to create winbinder timer object for timer with id '.$this->id);

        $this->isRunning = true;

        return $this;
    }

    /**
     * This method runs the callback of the timer.
     *
     * You should never call this method manually.
     *
     * @return void
     */
    public function run() {
        if(!$this->isRunning)
            throw new TimerNotRunningException($this);

        $this->callback->__invoke();
    }

    /**
     * This method destroys the timer, i.e. the associated callback will no longer be executed.
     *
     * @return Woody\Components\Timer\Timer $this
     */
    public function destroy() {
        if(!$this->isRunning)
            throw new TimerNotRunningException($this);

        $this->isRunning = false;

        EventHandler::removeEventHandler($this);

        if(!wb_destroy_timer($this->window->getControlID(), $this->id))
            throw new WinBinderException('Unable to destroy winbinder timer object for timer with id '.$this->id);

        return $this;
    }

    /**
     * This method return the id of the timer.
     *
     * @return int
     */
    public function getID() {
        return $this->id;
    }
}