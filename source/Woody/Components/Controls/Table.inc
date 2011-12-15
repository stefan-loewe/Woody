<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Model\TableModel;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Table extends Control implements \SplObserver {

    /**
     * the table model of the table
     *
     * @var TableModel
     */
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
     * This method returns the model of the table.
     *
     * @return \Woody\Model\TableModel
     */
    public function getModel() {
        return $this->model;
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
     * This method updates the table with the data of the new table model.
     *
     * @param \SplSubject $tableModel the new table model containing the new data
     * @return \Woody\Components\Controls\Table $this
     */
    public function update(\SplSubject $tableModel) {
        $this->model = $tableModel;

        // clear all entries from the table, and rebuild it with the new data
        $this->clear();
        $this->fillColumnHeaders();
        $this->fillDataCells();

        return $this;
    }

    /**
     * This method clears the table, i.e. delete its headers and data.
     *
     * @return Table
     */
    private function clear() {
        wb_create_items($this->getControlID(), null, TRUE);

        return $this;
    }

    /**
     * This method fills the header of the table. This is a mandatory step before being able to insert any data.
     *
     * @return \Woody\Components\Controls\Table $this
     */
    private function fillColumnHeaders() {
        $headers        = new \ArrayObject();
        $columnCount    = $this->model->getColumnCount();

        for($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
            $headers[] = $this->model->getColumnName($columnIndex);
        }

        wb_set_text($this->controlID, $headers->getArrayCopy());

        return $this;
    }

    /**
     * This method fills the table with the data from the model.
     *
     * @return Table
     */
    private function fillDataCells() {
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

    /**
     * This method returns the currently selected index of the table.
     *
     * @return int
     */
    public function getSelectedIndex() {
        if(($selection = wb_get_selected($this->controlID)) !== null)
            return new \ArrayObject($selection);

        else
            return new \ArrayObject();
    }

    /**
     * This method sets the currently selected index of the table.
     *
     * @param int $index the index to set
     * @return \Woody\Components\Controls\Table $this
     */
    public function setSelectedIndex($index) {

        wb_set_selected($this->controlID, $index);

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