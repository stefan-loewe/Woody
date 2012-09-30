<?php

namespace ws\loewe\Woody\Dialog\Notification;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;

abstract class ConfirmationDialog extends ModalSystemDialog {
  protected function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct($style, $title, $text, $parentWindow);
  }
}