<?php

namespace Woody\Model;

class DefaultTableModel extends TableModel {
  /**
   * the two-dimensional array containing the data of the table
   *
   * @var array
   */
  protected $data = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param array $data the data of the table model.
   */
  public function __construct(array $data) {
    parent::__construct();

    $this->data = $data;
  }

  public function getEntry($row, $column) {
    return $this->data[$row][$column];
  }

  public function getRowCount() {
    return count($this->data);
  }

  public function getColumnCount() {
    $columnCount = 0;

    foreach($this->data as $row) {
      $columnCount = max($columnCount, count($row));
    }

    return $columnCount;
  }

  /**
   * This method sets the data of the table model.
   *
   * @param array $data the data of the table model
   */
  public function setData(array $data) {
    $this->data = $data;

    $this->notify();
  }
}