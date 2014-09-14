<?php

namespace ws\loewe\Woody\Components\Timer;

use Closure;
use ws\loewe\Woody\Components\Windows\AbstractWindow;
use ws\loewe\Woody\Event\TimeoutAdapter;

class RunOnceTimer extends Timer {

  public function __construct(Closure $callback, AbstractWindow $window, $interval) {
    parent::__construct($callback, $window, $interval);

    $this->addTimeoutListener(new TimeoutAdapter(function () {
      if($this->counter == 1) {
        $this->destroy();
      }
    }));
  }
}