<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Model\ListModel;

class ListView extends ListControl
{
    const DEFAULT_HEIGHT        = 100;

    const AUTO_WIDTH            = -2;

    const COLUMN_BORDER_WIDTH   = 2;

    public function __construct(Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct($topLeftCorner, $dimension);

        $this->type = ListView;

        $this->cellRenderer = self::getDefaultCellRenderer();
    }

    public static function getDefaultCellRenderer()
    {
        $callback = function($element)
                    {
                        return array("1", "2", "3");
                    };

        return $callback;
    }

    /**
     * This method sets the headers of the list view. This is a mandatory step, otherwise no data entries maybe inserted.
     *
     * @param array $columns the tiltes of the header as array of strings
     * @return $this
     */
    public function setColumns(array $columns)
    {
        wb_set_text($this->controlID, $columns);

        if(true/*$this->width == ListView::AUTO_WIDTH*/)
        {
            $width = 0;
            foreach($columns as $column)
                $width = $width + $column[1] + ListView::COLUMN_BORDER_WIDTH;

            //$this->resizeTo(new Dimension($width, $this->dimension->height));
        }

        return $this;
    }

    public function update(\SplSubject $listModel)
    {
        // elements were added => add the respective number of rows to the end of the list
        if(($rowCountDifference = $listModel->count() - $this->count()) > 0)
        {
            for($i = 0; $i < $rowCountDifference; ++$i)
                $this->addRow(array(null));
        }
        else
        {
            // elements were deleted => delete the respective number of rows from the end of the list
            for($i = $rowCountDifference; $i < 0; ++$i)
                $this->deleteRow($this->count() - 1);
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
     * @return void
     */
    private function addRow(array $data)
    {
        wb_create_items($this->getControlID(), $data);

        return $this;
    }

    /**
     * This method updates the contents of a row.
     *
     * @param array data the data of the row - passing array(null) creates an empty row
     * @param int index the index of the row to be updated
     * @return void
     */
    private function updateRow(array $data, $index)
    {
        wb_set_text($this->getControlID(), $data, $index);

        return $this;
    }

    /**
     * This method delete a row.
     *
     * @param int index the index of the row to be deleted
     * @return void
     */
    private function deleteRow($index)
    {
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
    private function count()
    {
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