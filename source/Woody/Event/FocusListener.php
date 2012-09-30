<?php

namespace ws\loewe\Woody\Event;

interface FocusListener {
  function focusGained(FocusEvent $event);
}