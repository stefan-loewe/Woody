<?php

namespace ws\loewe\Woody\Event;

interface WindowResizeListener {
  function windowResized(WindowResizeEvent $event);
}