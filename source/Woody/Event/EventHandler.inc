<?php

namespace Woody\Event;

class EventHandler
{
    /**
     * the collection of controls fow which even handlers are registerd
     *
     * This is a primitive array, as a an object is cumbersome to initialize for a static class
     *
     * @var array
     */
    private static $controls = array();

    private static $previousEvent = null;

    /**
     * This method acts as the constructor of the class - as it is a static class, no instantiation is possible.
     */
    private function __construct()
    {
    }

    public static function addEventHandler($control, $handler)
    {
       self::$controls[$control->getID()] = array($control, $handler);

       var_dump('adding handler for control '.$control->getID());
    }

    public static function removeEventHandler($control)
    {
       unset(self::$controls[$control->getID()]);
    }

    public static function handleEvent(Event $event)
    {
        if(!self::isValidEvent($event))
            return;

        self::$previousEvent = $event;

        var_dump('calling handleEvent firing event ('.  microtime(TRUE).') ...');
        echo ($event);
        if($event->id === IDCLOSE)
        {
            wb_destroy_window($event->windowID);
            return;
        }

        if(self::isTimerEvent($event->windowID, $event->controlID))
        {
            $pair = self::$controls[$event->id];

            $control    = $pair[0];
            $callback   = $pair[1];

            $callback->__invoke($control);

            return;
        }

        $pair = self::$controls[$event->id];
        $control    = $pair[0];
//var_dump($pair[0]);
        $callback   = $pair[1];

        if($callback === null)
            return;

        $callback->__invoke($control, $event->param1, $event->param2);
return;
        $pair = self::$controls[$event->controlID];

        if($pair === null)
        {
            var_dump("no callback defined, nothing to handle");
        }
        else
        {
        /*    $control    = $pair[0];
            $callback   = $pair[1];

            $callback->__invoke($control);*/
        }
    }

    private static function isTimerEvent($windowID, $controlID)
    {
        return $windowID == $controlID;
    }

    private static function isValidEvent($event)
    {
        if(self::$previousEvent === null)
            return TRUE;

        var_dump('diff = '.($event->time - self::$previousEvent->time));

        // selecting a new entry in a ListView fires three identical events
        // this tries to filter out these
        return ($event->time - self::$previousEvent->time) > 0.1;
    }
}
