<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

class YesNoConfirmationDialog extends ConfirmationDialog {
  public function __construct($title, $text, AbstractWindow $parentWindow = null) {
    parent::__construct(WBC_YESNO, $title, $text, $parentWindow);
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
}