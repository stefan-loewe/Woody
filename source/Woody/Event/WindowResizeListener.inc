<?php

namespace Woody\Event;

interface WindowResizeListener {
  function windowResized(WindowResizeEvent $event);
}