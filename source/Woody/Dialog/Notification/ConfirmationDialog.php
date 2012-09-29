<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

abstract class ConfirmationDialog extends ModalSystemDialog {
  protected function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct($style, $title, $text, $parentWindow);
  }
}