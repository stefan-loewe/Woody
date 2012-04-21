<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

class DefaultMessageDialog extends MessageDialog {
  public function __construct($title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct(WBC_OK, $title, $text, $parentWindow);
  }
}