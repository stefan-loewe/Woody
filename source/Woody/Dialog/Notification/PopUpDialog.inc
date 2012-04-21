<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

abstract class PopUpDialog {
  /**
   * the title of the dialog
   *
   * @var string
   */
  protected $title = null;

  /**
   * the message of the dialog
   *
   * @var string
   */
  protected $text = null;

  /**
   * the window the dialog belongs to, may be null
   *
   * @var \Woody\Components\Windows\AbstractWindow
   */
  protected $window = null;

  /**
   * the style of the dialog, i.e. encodes which dialog to render
   *
   * @var int
   */
  protected $style = null;

  /**
   * the state of the dialog, i.e. encodes which button was pressed to close the dialog
   *
   * @var int
   */
  protected $state = null;

  /**
   * This method acts as the constructor of the class.
   */
  protected function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
    $this->style  = $style;
    $this->title  = $title;
    $this->text   = $text;
    $this->window = $parentWindow;
  }

  /**
   * This method opens the dialog.
   */
  public function open() {
    $this->state = wb_message_box($this->window, $this->text, $this->title, $this->style);
  }
}