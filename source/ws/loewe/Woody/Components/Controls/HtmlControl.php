<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\Components\Component;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class HtmlControl extends Control {
  /**
   * This method acts as the constructor of the class.
   *
   * @param string $url the url where the html control points to
   * @param Point $topLeftCorner the top left corner of the html control
   * @param Dimension $dimension the dimension of the html control
   */
  public function __construct($url, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($url, $topLeftCorner, $dimension);

    $this->type = HTMLControl;
  }

  protected function create(Component $parent) {
    parent::create($parent);

    $this->setUrl($this->value);
  }

  /**
   * This method returns the URL to which the control currently points.
   *
   * @return string the URL to which the control currently points
   */
  public function getUrl() {
    return $this->value;
  }

  /**
   * This method sets the URL to which the control should point.
   *
   * @param string $url the URL to which the control should point
   * @return HtmlControl $this
   */
  public function setUrl($url) {
    $this->value = $url;

    wb_set_location($this->controlID, $this->value);

    return $this;
  }
}