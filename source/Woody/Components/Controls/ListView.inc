<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Model\ListModel;

class ListView extends ListControl {
call this Table, and add a TableModel, similar as Java does
remove it from the ListControl inheritance
    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the list view
     * @param Dimension $dimension the dimension of the list view
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($topLeftCorner, $dimension);

        $this->type = ListView;

        $this->cellRenderer = self::getDefaultCellRenderer();
    }

    /**
     * This method returns the default cell renderer for the list view, which actually creates an empty row.
     *
     * @return \Callable
     */
    protected static function getDefaultCellRenderer() {
        return function($element) {
                        return array();
                    };
    }

    /**
     * This method sets the headers of the list view. This is a mandatory step, otherwise no data entries maybe inserted.
     *
     * @param array $columns the tiltes of the header as array of strings
     * @return \Woody\Components\Controls\ListView $this
     * @todo pass a ListViewRenderer or somethig like that in, which defines headers, and width
     */
    public function setColumns(array $columns) {
        $columnWidth = floor($this->dimension->width / count($columns));

        foreach($columns as $index => $columnHeader) {
            $columns[$index] = array($columnHeader, $columnWidth - (($index == count($columns) - 1) ? 1 : 2));
        }
        wb_set_text($this->controlID, $columns);

        return $this;
    }

    /**
     * This method updates the
     *
     * @param \SplSubject $listModel
     * @return \Woody\Components\Controls\ListView $this
     */
    public function update(\SplSubject $listModel) {
        // elements were added => add the respective number of blank rows to the end of the list
        if(($rowCountDifference = $listModel->count() - $this->count()) > 0) {
            for($i = 0; $i < $rowCountDifference; ++$i) {
                $this->addRow(array(null));
            }
        } else {
            // elements were deleted => delete the respective number of rows from the end of the list
            for($i = $rowCountDifference; $i < 0; ++$i) {
                $this->deleteRow($this->count() - 1);
            }
        }

        if(($count = $listModel->count()) > 0) {
            for($i = 0; $i < $count; $i++) {
                $this->updateRow($this->cellRenderer->__invoke($listModel->getElementAt($i)), $i);
            }
        }

        return $this;
    }

    /**
     * This method adds a new row, containing the passed data.
     *
     * @param array data the data of the new row - passing array(null) creates an empty row
     * @return \Woody\Components\Controls\ListView $this
     */
    private function addRow(array $data) {
        wb_create_items($this->getControlID(), $data);

        return $this;
    }

    /**
     * This method updates the contents of a row.
     *
     * @param array data the data of the row - passing array(null) creates an empty row
     * @param int index the index of the row to be updated
     * @return \Woody\Components\Controls\ListView $this
     */
    private function updateRow(array $data, $index) {
        wb_set_text($this->getControlID(), $data, $index);

        return $this;
    }

    /**
     * This method delete a row.
     *
     * @param int index the index of the row to be deleted
     * @return \Woody\Components\Controls\ListView $this
     */
    private function deleteRow($index) {
        wb_delete_items($this->getControlID(), $index);

        return $this;
    }

    /**
     * This method returns the number of elements currently available in the list.
     *
     * This is not neccessarily the same as the count of the list model, as the list model may be updated, while the list view still holds the items of the list model before the respectice update.
     *
     * @return int the numer of element in the list
     */
    private function count() {
        return wb_get_item_count($this->getControlID());
    }
}
/*
class CheckableListView extends ListView
{
    public function __construct(ListView $listView)
    {
        $this->style = $listView->style | WBC_CHECKBOXES;

        $this->parentControl    = $listView->parentControl;
        $this->id               = $listView->id;
        $this->type             = $listView->type;
        $this->value            = $listView->value;
        $this->xPos             = $listView->xPos;
        $this->yPos             = $listView->yPos;
        $this->width            = $listView->width;
        $this->height           = $listView->height;
        $this->param            = $listView->param;
        $this->tabIndex         = $listView->tabIndex;
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

class ListViewHeader {

    private $columns = null;

    public function __construct() {
        $this->columns = new \ArrayObject();
    }

    public function addColumn($columnName, $columnWidth) {
        $this->columns[$columnName] = $columnWidth;
    }
}