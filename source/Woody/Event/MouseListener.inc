<?php

namespace Woody\Event;

interface MouseListener {
    public function mousePressed(MouseEvent $event);
    
    public function mouseReleased(MouseEvent $event);
}