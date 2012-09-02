<?php

namespace Woody\Event;

interface WindowCloseListener {
  function windowClosed(WindowCloseEvent $event);
}