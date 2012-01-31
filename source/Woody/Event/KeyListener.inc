<?php

namespace Woody\Event;


interface KeyListener {
    function keyPressed(KeyEvent $event);
    function keyReleased(KeyEvent $event);
}