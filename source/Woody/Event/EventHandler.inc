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

    public static function handleEvent($windowID, $id, $controlID, $param1, $param2)
    {
        if($id === IDCLOSE)
        {
            wb_destroy_window($windowID);
            return;
        }

        if(self::isTimerEvent($windowID, $controlID))
        {
            $pair = self::$controls[$id];

            $control    = $pair[0];
            $callback   = $pair[1];

            $callback->__invoke($control);

            return;
        }

        $pair = self::$controls[$id];
        $control    = $pair[0];
var_dump($pair[0]);
        $callback   = $pair[1];
        $callback->__invoke($control, $param1, $param2);
return;
        $pair = self::$controls[$controlID];

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
}
