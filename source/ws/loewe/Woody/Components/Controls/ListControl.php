<?php

namespace ws\loewe\Woody\Components\Controls;

use BadMethodCallException;
use SplObserver;
use SplSubject;
use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;
use ws\loewe\Woody\Model\ListModel;

abstract class ListControl extends Control implements SplObserver, Actionable {
  /**
   * the model of the list control
   *
   * @var ListModel
   */
  protected $model = null;

  /**
   * the cell rederer of the list control
   *
   * @var \callable
   */
  protected $cellRenderer = null;

  /**
   * constant for editable list control style
   */
  const EDITABLE = 0x00000000;

  /**
   * constant for non-editable list control style
   */
  const NON_EDITABLE = 0x00000040;

  /**
   * constant for index refering to an empty selection, i.e. no entry is selected in the list control
   */
  const NO_SELECTION = -1;

  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the list control
   * @param Dimension $dimension the dimension of the list control
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->cellRenderer = static::getDefaultCellRenderer();
  }

  /**
   * This method returns the default cell item for the tree view, which returns the result of __toString for objects
   * implementing it and the plain passed in element if not.
   *
   * @return \Callable the default cell renderer
   */
  protected static function getDefaultCellRenderer() {
    $callback = function($element) {
      if(is_callable(array($element, '__toString'), FALSE)) {
        return $element->__toString();
      }
      else {
        return $element;
      }
    };

    return $callback;
  }

  /**
   * This method returns the list model of the list control.
   *
   * @return ListModel
   */
  public function getModel() {
    return $this->model;
  }

  /**
   * This method sets the list model of the list control.
   *
   * @param ListModel $model the model to set
   * @return ListControl $this
   */
  public function setModel(ListModel $model) {
    return $this->update($model);
  }

  /**
   * This method sets the cell renderer of the list control.
   *
   * @param \Callable $cellRenderer
   * @return ListControl $this
   */
  public function setCellRenderer(callable $cellRenderer) {
    $this->cellRenderer = $cellRenderer;

    return $this;
  }

  /**
   * This method updates the content of the list control with the elements given in the list model.
   *
   * @param SplSubject $listModel the model to update the list control with
   * @return ListControl $this
   */
  public function update(SplSubject $listModel) {
    $this->model = $listModel;

    $options = array();

    if(($count = $this->model->count()) > 0) {
      for($i = 0; $i < $count; $i++) {
        $options[] = $this->cellRenderer->__invoke($this->model->getElementAt($i));
      }
    }

    wb_set_text($this->controlID, $options);

    $this->setSelectedIndex(self::NO_SELECTION);

    return $this;
  }

  /**
   * This method returns the currently selected index.
   *
   * @return int
   */
  public function getSelectedIndex() {
    return wb_get_selected($this->controlID);
  }

  /**
   * This method sets the currently selected index.
   *
   * @param int $index the index to set
   * @return ListControl $this
   */
  public function setSelectedIndex($index) {

    if(!$this->isValidIndex($index)) {
      $index = self::NO_SELECTION;
    }

    wb_set_selected($this->controlID, $index);

    return $this;
  }

  /**
   * This method checks if the given index is valid for the list control, with the current model.
   *
   * @param int $index the index to verify
   * @return boolean true, if the index is valid, else false
   */
  private function isValidIndex($index) {
    if($index === self::NO_SELECTION) {
      return true;
    }
    else if($index < self::NO_SELECTION) {
      return false;
    }
    else if($this->model == null) {
      return false;
    }
    else if($index >= $this->model->count()) {
      return false;
    }

    return true;
  }

  /**
   * This method clears the selection, i.e. afterwards, no entry is selected
   *
   * @return ListControl $this
   */
  public function clearSelection() {
    wb_set_selected($this->controlID, self::NO_SELECTION);

    return $this;
  }

  /**
   * This method gets the currently selected element.
   *
   * @return mixed
   */
  public function getSelectedElement() {
    $value = null;

    if(($selectedIndex = $this->getSelectedIndex()) >= 0) {
      $value = $this->model->getElementAt($selectedIndex);
    }

    return $value;
  }

  /**
   * This method sets the selected element.
   *
   * @param mixed $selectedElement the element to select
   * @return ListControl $this
   */
  public function setSelectedElement($selectedElement) {
    if($this->model !== null) {
      wb_set_selected($this->controlID, $this->model->getIndexOf($selectedElement));
    }

    return $this;
  }

  /**
   * This method sets the list control to be non-editable, i.e., the user cannot
   * extend the list with custom entries.
   *
   * @throws BadMethodCallException when trying to call this method after the
   * control was added to the UI.
   */
  public function setNonEditable() {

    if($this->controlID != null) {
      throw new BadMethodCallException('You cannot set styles after the control is added to the UI.');
    }

    $this->style = self::NON_EDITABLE;
  }
}