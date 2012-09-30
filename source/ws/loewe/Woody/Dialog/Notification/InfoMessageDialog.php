<?php

namespace ws\loewe\Woody\Dialog\Notification;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;

class InfoMessageDialog extends MessageDialog {
  public function __construct($title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct(WBC_INFO, $title, $text, $parentWindow);
  }
}