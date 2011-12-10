<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

abstract class TableModel implements \SplSubject {

    protected $data         = null;

    protected $observers    = null;

    protected $headers      = null;

    public function __construct($data) {
        $this->data = $data;

        $this->initializeColumnHeaders();
    }

    abstract function getRowCount();

    abstract function getColumnCount();

    abstract function getEntry($row, $column);

    public function getColumnName($columnIndex) {
        return $this->headers[$columnIndex];
    }

    private function initializeColumnHeaders() {
        $columnCount = $this->getColumnCount();

        for($i = 0; $i < $columnCount; $i++) {
            $this->headers[] = $this->toBase26($i);
        }
    }

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
        /*if(!$this->isAdjusting)*/ {
            foreach($this->observers as $observer) {
                $observer->update($this);
            }
        }

        return $this;
    }
}

class DefaultTableModel extends TableModel {

    public function __construct(array $data) {
        parent::__construct($data);
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
}

class Table extends Control {

    protected $model = null;
    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the list view
     * @param Dimension $dimension the dimension of the list view
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->type = ListView;
    }

    /**
     * This method sets the table model of the table.
     *
     * @param \Woody\Model\TableModel $model the model to set
     * @return \Woody\Components\Controls\Table $this
     */
    public function setModel(TableModel $model) {
        return $this->update($model);
    }

    /**
     * This method updates the
     *
     * @param \SplSubject $tableModel
     * @return \Woody\Components\Controls\Table $this
     */
    public function update(\SplSubject $tableModel) {
        $this->model = $tableModel;

        $this->clear();
        $this->setHeader();
        $this->setData();

        return $this;
    }

    private function clear() {
        wb_create_items($this->getControlID(), null, TRUE);

        return $this;
    }

    /**
     * This method sets the header of the table. This is a mandatory step before being able to insert any data.
     *
     * @return \Woody\Components\Controls\Table $this
     */
    private function setHeader() {
        $headers        = new \ArrayObject();
        $columnCount    = $this->model->getColumnCount();

        for($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
            $headers[] = $this->model->getColumnName($columnIndex);
        }

        wb_set_text($this->controlID, $headers->getArrayCopy());

        return $this;
    }

    private function setData() {
        $data           = array();

        $rowCount       = $this->model->getRowCount();
        $columnCount    = $this->model->getColumnCount();

        for($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
            $data[$rowIndex] = array();
            for($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
                $data[$rowIndex][$columnIndex] = $this->model->getEntry($rowIndex, $columnIndex);
            }
        }
        wb_create_items($this->getControlID(), $data);

        return $this;
    }
}
/*
class CheckableTable extends Table
{
    public function __construct(Table $Table)
    {
        $this->style = $Table->style | WBC_CHECKBOXES;

        $this->parentControl    = $Table->parentControl;
        $this->id               = $Table->id;
        $this->type             = $Table->type;
        $this->value            = $Table->value;
        $this->xPos             = $Table->xPos;
        $this->yPos             = $Table->yPos;
        $this->width            = $Table->width;
        $this->height           = $Table->height;
        $this->param            = $Table->param;
        $this->tabIndex         = $Table->tabIndex;
    }

    public function getCheckedValues()
    {
        $values = null;

        if(($selectedIndices = wb_get_value($this->controlID)) != null)
        {
            $values = new ArrayObject();

            foreach($selectedIndices as $selectedIndex)
                $values[] = $this->model->getElementAt($selectedIndex);
        }

        return $values;
    }
}
*/

class TableHeader {

    private $columns = null;

    public function __construct() {
        $this->columns = new \ArrayObject();
    }

    public function addColumn($columnName, $columnWidth) {
        $this->columns[$columnName] = $columnWidth;
    }
}