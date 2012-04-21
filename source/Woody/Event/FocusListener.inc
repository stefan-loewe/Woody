<?php

namespace Woody\Event;

interface FocusListener {
  function focusGained(FocusEvent $event);
}