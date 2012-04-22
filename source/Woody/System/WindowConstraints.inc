<?php

namespace Woody\System;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Components\Windows\MainWindow;

class WindowConstraints {
  /**
   * the lowest width that is possible for a window on the current machine, with the current display settings
   *
   * @var int
   */
  private $minWidth = null;

  /**
   * the lowest height that is possible for a window on the current machine, with the current display settings
   *
   * @var int
   */
  private $minHeight = null;

  /**
   * the highest width that is possible for a window on the current machine, with the current display settings
   *
   * @var int
   */
  private $maxWidth = null;

  /**
   * the highest height that is possible for a window on the current machine, with the current display settings
   *
   * @var int
   */
  private $maxHeight = null;

  /**
   * the instance that this singleton encapsulates
   *
   * @var Woody\System\WindowConstraints
   */
  private static $instance = null;

  /**
   * a winbinder window handle to derive the constraints
   *
   * @var int
   */
  private $window = null;

  /**
   * This method acts as the constructor of the class.
   */
  private function __construct() {
    $this->window = wb_create_window(NULL,
      AppWindow,
      'initializing ...',
      WBC_CENTER,
      WBC_CENTER,
      0,
      0,
      WBC_INVISIBLE
    );

    $this->determineMinima();
    $this->determineMaxima();

    wb_destroy_window($this->window);
  }

  public function __get($memberName) {
    return $this->$memberName;
  }

  /**
   * This method returns the sole instance of this class.
   *
   * @return Woody\System\WindowConstraints the sole instance of this class
   */
  public static function getInstance() {
    if(self::$instance === null) {
      self::$instance = new WindowConstraints();
    }

    return self::$instance;
  }

  /**
   * This method calculates and sets the minimal width and height a window may have with the current display settings.
   *
   * @return Woody\System\WindowConstraints $this
   */
  private function determineMinima() {
    wb_set_size($this->window, 0, 0);
    $dimension = wb_get_size($this->window);

    $this->minWidth = $dimension[0];
    $this->minHeight = $dimension[1];

    return $this;
  }

  /**
   * This method calculates and sets the maximal width and height a window may have with the current display settings.
   *
   * @return Woody\System\WindowConstraints $this
   */
  private function determineMaxima() {
    wb_set_size($this->window, 99999, 99999);
    $dimension = wb_get_size($this->window);

    $this->maxWidth = $dimension[0];
    $this->maxHeight = $dimension[1];

    return $this;
  }

  public function sanatize(Dimension $dimension) {
    $width = min($this->maxWidth, max($this->minWidth, $dimension->width));
    $height = min($this->maxHeight, max($this->minHeight, $dimension->height));

    return new Dimension($width, $height);
  }
}