<?php

namespace ws\loewe\Woody\Event;

interface MouseListener {
  function mousePressed(MouseEvent $event);

  function mouseReleased(MouseEvent $event);
}