<?php

namespace Woody\Components\Timer;

use Woody\Components\Component;
use Woody\Components\Windows\AbstractWindow;
use Woody\Event\EventHandler;

class Timer
{
    /**
     * the id of the timer
     *
     * @var int
     */
    protected $id           = null;

    /**
     * the window to which the timer is bound to
     *
     * @var Woody\Components\Windows\AbstractWindow
     */
    protected $window       = null;

    /**
     * the callback that is executed once the timer is activated
     *
     * @var Closure
     */
    protected $callback     = null;

    /**
     * the number of milliseconds that pass between each call to the callback of the timer
     *
     * @var int
     */
    protected $interval     = null;

    /**
     * This method acts as the constructor of the timer.
     *
     * @param Closure $callback the callback that is executed once the timer is activated
     * @param int $interval the number of milliseconds that pass between each call to the callback of the timer
     */
    public function __construct(\Closure $callback, $interval)
    {
        $this->id       = Component::getUniqueID();

        $this->callback = $callback;

        $this->interval = $interval;
    }

    /**
     * This method starts the timer.
     *
     * @param AbstractWindow $window the window to which the timer is bound to.
     * @return Woody\Components\Timer\Timer $this
     */
    public function start(AbstractWindow $window)
    {
        $this->window   = $window;

        EventHandler::addEventHandler($this, $this->callback);

        wb_create_timer($this->window->getControlID(), $this->id, $this->interval);

        return $this;
    }

    /**
     * This method runs the callback of the timer. Never call this method manually.
     */
    public function run()
    {
        $this->callback->__invoke();
    }

    /**
     * This method destroys the timer, i.e. the associated callback will no longer be executed.
     *
     * @return Woody\Components\Timer\Timer $this
     */
    public function destroy()
    {
        wb_destroy_timer($this->window->getControlID(), $this->id);

        EventHandler::removeEventHandler($this);

        return $this;
    }

    public function getID()
    {
        return $this->id;
    }
}