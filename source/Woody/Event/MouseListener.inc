<?php

namespace Woody\Event;

interface MouseListener {
    function mousePressed(MouseEvent $event);

    function mouseReleased(MouseEvent $event);
}