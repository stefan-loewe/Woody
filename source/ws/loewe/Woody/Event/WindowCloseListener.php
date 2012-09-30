<?php

namespace ws\loewe\Woody\Event;

interface WindowCloseListener {
  function windowClosed(WindowCloseEvent $event);
}