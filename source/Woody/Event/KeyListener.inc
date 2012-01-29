<?php

namespace Woody\Event;


interface KeyListener {
    public function keyPressed(KeyEvent $event);
    public function keyReleased(KeyEvent $event);
}