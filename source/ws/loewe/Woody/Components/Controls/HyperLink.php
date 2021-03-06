<?php

namespace ws\loewe\Woody\Components\Controls;

use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;

class HyperLink extends Control implements Actionable {

  /**
   * the url to which the hyperlink control points
   *
   * @var string
   */
  private $url;

  /**
   * This method acts as the constructor of the class.
   *
   * @param string $url the url to which the hyperlink control points
   * @param string $label the label of the hyperlink control
   * @param Point $topLeftCorner the top left corner of the hyperlink control
   * @param Dimension $dimension the dimension of the hyperlink control
   */
  public function __construct($url, $label, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($label, $topLeftCorner, $dimension);

    $this->type   = HyperLink;
    $this->style  = $this->style | WBC_LINES;
    $this->param  = $this->param | BLUE;

    $this->url    = $url;
  }

  /**
   * This method returns the label of the hyperlink control
   *
   * @return string the label of the hyperlink control
   */
  public function getLabel() {
    return wb_get_text($this->controlID);
  }

  /**
   * This method sets the label of the hyperlink control
   *
   * @param string $label the label of the hyperlink control
   * @return HyperLink $this
   */
  public function setLabel($label) {
    wb_set_text($this->controlID, $label);

    return $this;
  }

  /**
   * This method returns the url to which the hyperlink points.
   *
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * This method sets the url to which the hyperlink points.
   *
   * @param string $url the url to which the hyperlink points
   */
  public function setUrl($url) {
    $this->url = $url;
  }
}