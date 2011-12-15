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
     * @param array $data a two-dimensional array containing the data of the table.
     */
    public function __construct(array $data) {
        parent::__construct();

        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getEntry($row, $column) {
        return $this->data[$row][$column];
    }

    /**
     * @inheritDoc
     */
    public function getRowCount() {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function getColumnCount() {
        $columnCount = 0;

        foreach($this->data as $row) {
            $columnCount = max($columnCount, count($row));
        }

        return $columnCount;
    }
}