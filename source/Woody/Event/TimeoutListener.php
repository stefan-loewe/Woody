<?php

namespace Woody\Event;

interface TimeoutListener {
  function timeout(TimeoutEvent $event);
}