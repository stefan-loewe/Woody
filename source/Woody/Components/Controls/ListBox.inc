<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ListBox extends SelectBox {

    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the list box
     * @param Dimension $dimension the dimension of the list box
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($topLeftCorner, $dimension);

        $this->type = ListBox;
    }
}
/*
class MultiSelectListBox extends ListBox
{
    public function __construct(ListBox $listbox)
    {
        $this->style = $listbox->style | WBC_MULTISELECT;

        $this->parentControl    = $listbox->parentControl;
        $this->type             = $listbox->type;
        $this->value            = $listbox->value;
        $this->xPos             = $listbox->xPos;
        $this->yPos             = $listbox->yPos;
        $this->width            = $listbox->width;
        $this->height           = $listbox->height;
        $this->param            = $listbox->param;
        $this->tabIndex         = $listbox->tabIndex;

        $this->id               = $listbox->id.'_multi';
        $this->mapping         = $listbox->mapping;
    }

    public function getValues()
    {
        $values = new ArrayObject();

        if(($maxCount = $this->getSelectionCount()) > 0)
        {
            //$str = str_repeat("\0", 4 * $maxCount); lead to "zend_mm_heap corrupted" when selecting values in multi-select listbox and then selecting a vlue from combobox !?!?!?!
            $str = str_repeat(' ', 4 * $maxCount);

            $strPtr = wb_get_address($str);

            // 0x191 = LB_GETSELITEMS
            $count = wb_send_message($this->controlID, 0x191, $maxCount, $strPtr);

            $selectedIndices = unpack('i'.$maxCount, $str);

            foreach($selectedIndices as $selectedIndex)
                $values[] = $this->model->getElementAt($selectedIndex);
        }

        return $values;
    }

    private function getSelectionCount()
    {
        // 0x190 = LB_GETSELCOUNT
        return wb_send_message($this->controlID, 0x190, null, null);
    }
}*/