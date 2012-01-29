<?php

namespace Woody\Event;


interface FocusListener {
    public function focusGained(/*FocusEvent*/ $event);
}