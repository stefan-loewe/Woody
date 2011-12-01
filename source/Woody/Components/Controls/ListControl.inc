<?php

namespace Woody\Components\Controls;

use \Woody\Model\ListModel;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

abstract class ListControl extends Control implements \SplObserver
{
    /**
     * the model of the list control
     *
     * @var ListModel
     */
    protected $model        = null;

    /**
     * the cell rederer of the list control
     *
     * @var \Callable
     */
    protected $cellRenderer = null;

    const EDITABLE      = 0x00000000;

    const NON_EDITABLE	= 0x00000040;

    public function __construct(Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->cellRenderer = static::getDefaultCellRenderer();
    }

    public static function getDefaultCellRenderer()
    {
        $callback = function($element) {
                        if(is_callable(array($element, '__toString'), FALSE)) {
                            return $element->__toString()."BBB";
                        } else {
                            return $element."AAA";
                        }
                    };

        return $callback;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(ListModel $model)
    {
        $this->model = $model;

        return $this;
    }

    public function setCellRenderer( $cellRenderer)
    {
        $this->cellRenderer = $cellRenderer;

        return $this;
    }

    public function update(\SplSubject $listModel)
    {
        $options        = array();

        $currentIndex	= max(0, $this->getSelectedIndex());

        if(($count = $listModel->count()) > 0) {
            for($i = 0; $i < $count; $i++) {
                $options[] = $this->cellRenderer->__invoke($listModel->getElementAt($i));
            }
        }

        wb_set_text($this->controlID, $options);

        $this->setSelectedIndex($currentIndex);

        return $this;
    }

    public function getSelectedIndex()
    {
        return wb_get_selected($this->controlID);
    }

    public function setSelectedIndex($index)
    {
        wb_set_selected($this->controlID, $index);

        return $this;
    }

    public function getSelectedValue()
    {
        $value = null;

        if(($selectedIndex = $this->getSelectedIndex()) >= 0)
            $value = $this->model->getElementAt($selectedIndex);

        return $value;
    }

    public function setSelectedValue($selectedValue)
    {
       wb_set_selected($this->controlID, $this->model->getIndexOf($selectedValue));

       return $this;
    }
}