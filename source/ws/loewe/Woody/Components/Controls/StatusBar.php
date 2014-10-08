<?php

namespace ws\loewe\Woody\Components\Controls;

use ws\loewe\Woody\Components\Windows\AbstractWindow;

/**
 * This class encapsulates a status bar
 *
 * @author loewe
 */
class StatusBar {

  private $controlID;
  private $text;

  public function __construct($text) {
    $this->text = $text;
  }

  public function add(AbstractWindow $window) {
    $this->controlID = wb_create_control($window->getControlID(), StatusBar, $this->text);
  }

  public function getText() {
    return $this->text;
  }

  public function setText($text) {
    $this->text = $text;

    wb_set_text($this->controlID, $this->text);

    return $this;
  }
}
