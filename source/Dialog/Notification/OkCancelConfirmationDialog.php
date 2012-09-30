<?php

namespace ws\loewe\Woody\Dialog\Notification;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;

class OkCancelConfirmationDialog extends ConfirmationDialog {
  public function __construct($title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct(WBC_OKCANCEL, $title, $text, $parentWindow);
  }

  /**
   * This method returns true if the ok button was clicked.
   *
   * @return boolean true, if the ok button was clicked, else false
   */
  public function ok() {
    return $this->state === TRUE;
  }

  /**
   * This method returns true if the cancel button was clicked.
   *
   * @return boolean true, if the cancel button was clicked, else false
   */
  public function cancel() {
    return $this->state === FALSE;
  }
}