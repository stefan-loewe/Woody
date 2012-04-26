<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

abstract class Control extends Component {
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

    $absoluteTopLeftCorner = $this->calculateAbsoluteTopLeftCorner();

    $this->controlID = wb_create_control(
      $parent->getWindow()->getControlID(),
      $this->type,
      $this->value,
      $absoluteTopLeftCorner->x,
      $absoluteTopLeftCorner->y,
      $this->dimension->width,
      $this->dimension->height,
      $this->id,
      $this->style,
      $this->param,
      $this->id
    );

    static::$components[$this->controlID] = $this;
  }

  /**
   * This method calculates the absolute position of the control.
   *
   * This is necessarry, as any control is not really bound to its parent control, but to the container it belongs to.
   * This is done, as some controls do not fire events properly when being bound to a frame instead of a window.
   * However, if the control is positioned, it has to be positioned relative to its logical parent, therefore, the
   * offset has to be taken into account.
   *
   * @return Point the actual top left corner of the control
   */
  private function calculateAbsoluteTopLeftCorner() {
    $absTopLeftCorner = $this->topLeftCorner;

    $parent = $this;
    while(($parent = $parent->parent) != null) {
      if($parent instanceof Frame) {
        $absTopLeftCorner = $absTopLeftCorner->moveBy(new Dimension($parent->topLeftCorner->x, $parent->topLeftCorner->y));
      }
    }

    return $absTopLeftCorner;
  }
}