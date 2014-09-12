<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class ListBoxMulti extends ListBox {

  private static $LB_GETSELCOUNT = 0x190;

  private static $LB_GETSELITEMS = 0x191;

  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the list box
   * @param Dimension $dimension the dimension of the list box
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($topLeftCorner, $dimension);

    $this->type	  = ListBox;
    $this->style  = $this->style | WBC_MULTISELECT;
  }

  /**
   * This method gets the currently selected elements.
   *
   * @return mixed[]
   */
  public function getSelectedElements() {
    $values = new \ArrayObject();

    if(($selectionCount = $this->getSelectionCount()) > 0) {
	// $str = str_repeat("\0", 4 * $maxCount); lead to "zend_mm_heap corrupted" when selecting values in
	// multi-select listbox and then selecting a value from combobox !?!?!?!
	$str = str_repeat(' ', 4 * $selectionCount);

	$strPtr = wb_get_address($str);

	wb_send_message($this->controlID, self::$LB_GETSELITEMS, $selectionCount, $strPtr);

	$selectedIndices = unpack('i'.$selectionCount, $str);

	foreach($selectedIndices as $selectedIndex) {
	    $values[] = $this->model->getElementAt($selectedIndex);
	}
    }

    return $values;
  }

  /**
   * This method determines the number of selected elements.
   *
   * @return int
   */
  public function getSelectionCount() {
    return wb_send_message($this->controlID, self::$LB_GETSELCOUNT, null, null);
  }

  /**
   * This method sets a selected element. The previously selected elements stay selected.
   *
   * @param mixed $element the element to set selected.
   * @return ListBoxMulti $this
   */
  public function setSelectedElement($element) {
    if($this->model->getIndexOf($element) !== -1) {
      wb_send_message($this->controlID, 0x0185, 1, $this->model->getIndexOf($element));
    }

    return $this;
  }
}