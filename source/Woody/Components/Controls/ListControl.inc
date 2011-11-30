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
    protected $model	= null;

    /**
     * the mapping of the list control, used to customize the output of each list control entry
     *
     * @var \Closure
    protected $mapping	= null;
     */

    const EDITABLE      = 0x00000000;

    const NON_EDITABLE	= 0x00000040;

    public function __construct(Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct(null, $topLeftCorner, $dimension);

        //$this->mapping = static::getDefaultMapping();
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

    public function getMapping()
    {
        return $this->mapping;
    }

    public function setMapping($mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }

    public function update(\SplSubject $listModel)
    {
        $options        = array();

        $currentIndex	= max(0, $this->getSelectedIndex());

        if($listModel->count() > 0)
        {
            $iterator = $listModel->getIterator();

            while($iterator->valid())
            {
                //if(($value = $this->mapping->__invoke($iterator->current())) !== null)
                    //$options[] = $value;
                $options[] = $iterator->current();

                $iterator->next();
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

    public function getValue()
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