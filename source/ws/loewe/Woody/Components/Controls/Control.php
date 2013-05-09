<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\Components\Component;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

abstract class Control extends Component {
  
  /**
   * the tab index of the control
   *
   * @var int
   */
  protected $tabIndex = null;
  
  /**
   * This method acts as the constructor of the class.
   *
   * @param mixed $value the value associated with the control
   * @param Point $topLeftCorner the top left corner of the control
   * @param Dimension $dimension the dimension of the control
   */
  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($value, $topLeftCorner, $dimension);
  }

  /**
   * This method creates the actual control as winbinder resource, and binds it to its parent control.
   *
   * @param Component $parent the parent component of the control
   */
  protected function create(Component $parent) {
    $this->parent = $parent;

    $this->controlID = wb_create_control(
      $parent->getControlID(),
      $this->type,
      $this->value,
      $this->topLeftCorner->x,
      $this->topLeftCorner->y,
      $this->dimension->width,
      $this->dimension->height,
      $this->id,
      $this->style,
      $this->param,
      ($this instanceof Frame && $parent instanceof Tab) ? ($this->tabIndex) : null
    );

    static::$components[$this->controlID] = $this;
  }
}