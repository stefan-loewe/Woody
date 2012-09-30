<?php

namespace ws\loewe\Woody\Dialog\Notification;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;

class YesNoCancelConfirmationDialog extends ConfirmationDialog {
  public function __construct($title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct(WBC_YESNOCANCEL, $title, $text, $parentWindow);
  }

  /**
   * This method returns true if the yes button was clicked.
   *
   * @return boolean true, if the yes button was clicked, else false
   */
  public function yes() {
    return $this->state === TRUE;
  }

  /**
   * This method returns true if the no button was clicked.
   *
   * @return boolean true, if the no button was clicked, else false
   */
  public function no() {
    return $this->state === 0;
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