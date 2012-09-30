<?php

namespace ws\loewe\Woody\Event;

interface TimeoutListener {
  function timeout(TimeoutEvent $event);
}