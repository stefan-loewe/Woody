<?php

namespace ws\loewe\Woody\System;

use \ws\loewe\Utils\Geom\Dimension;

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
   * the instance that this singleton encapsulates
   *
   * @var ws\loewe\Woody\System\WindowConstraints
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
    $this->window = wb_create_window(
      NULL,
      AppWindow,
      'initializing ...',
      WBC_CENTER,
      WBC_CENTER,
      0,
      0,
      WBC_INVISIBLE
    );

    $this->determineMinima();

    wb_destroy_window($this->window);
  }

  /**
   * This method returns the sole instance of this class.
   *
   * @return ws\loewe\Woody\System\WindowConstraints the sole instance of this class
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
   * @return ws\loewe\Woody\System\WindowConstraints $this
   */
  private function determineMinima() {
    wb_set_size($this->window, 0, 0);
    $dimension = wb_get_size($this->window);

    $this->minWidth   = $dimension[0];
    $this->minHeight  = $dimension[1];
    return $this;
  }

  /**
   * This method enforces the contraints on the given dimension, and mutates it accordingly.
   *
   * @param Dimension $dimension the dimension to enforce the contraints on
   * @return ws\loewe\Utils\Geom\Dimension the dimension with the contraints enforced
   */
  public function enforceConstraints(Dimension $dimension) {
    $width  = max($this->minWidth, $dimension->width);
    $height = max($this->minHeight, $dimension->height);

    return new Dimension($width, $height);
  }
}