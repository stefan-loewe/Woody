<?php

namespace Woody\Model;

abstract class TableModel implements \SplSubject {
  /**
   * the collection of observers
   *
   * @var \SplObjectStorage
   */
  protected $observers = null;

  /**
   * the collection of headers of the columns
   *
   * @var \ArrayObject
   */
  protected $headers = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    $this->observers = new \SplObjectStorage();

    $this->headers = new \ArrayObject();
  }

  /**
   * This method return the number of rows the table model contains.
   */
  abstract function getRowCount();

  /**
   * This method return the number of columns the table model contains.
   */
  abstract function getColumnCount();

  /**
   * This method return entry of the table model at the specified position.
   *
   * @param int $rowIndex the row index from where to get the entry
   * @param int $columnIndex the column index from where to get the entry
   * @return mixed the entry at the specified position
   */
  abstract function getEntry($rowIndex, $columnIndex);

  /**
   * This method returns the name of the column at the specified column index.
   *
   * @param int $columnIndex the column index to get the the name for
   * @return string the name of the column
   */
  public function getColumnName($columnIndex) {
    if(isset($this->headers[$columnIndex])) {
      return $this->headers[$columnIndex];
    }
    else {
      return $this->toBase26($columnIndex);
    }
  }

  /**
   * This method transforms an decimal integer to its represenation to the base of 26, represented with charachters
   * between A and Z.
   *
   * @param int $i the integer to convert
   * @return string the representation to the base of 26 of the given integer
   */
  private function toBase26($i) {
    $result = '';

    do {
      $digit = $i % 26;
      $i = floor($i / 26) - 1;

      $result = chr(65 + $digit).$result;
    } while($i >= 0);

    return $result;
  }

  /**
   * This method adds an observer to the model.
   *
   * @param \SplObserver $observer the observer to add
   * @return \Woody\Model\TableModel $this
   */
  public function attach(\SplObserver $observer) {
    $this->observers->attach($observer);

    return $this;
  }

  /**
   * This method removes an observer to the model.
   *
   * @param \SplObserver $observer the observer to remove
   * @return \Woody\Model\TableModel $this
   */
  public function detach(\SplObserver $observer) {
    $this->observers->detach($observer);

    return $this;
  }

  /**
   * This method notifies all observer of the model to update themselves.
   *
   * @return \Woody\Model\TableModel $this
   */
  public function notify() {
    /* if(!$this->isAdjusting) */ {
      foreach($this->observers as $observer) {
        $observer->update($this);
      }
    }

    return $this;
  }
}