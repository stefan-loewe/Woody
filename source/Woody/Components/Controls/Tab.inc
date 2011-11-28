<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Components\Container;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Tab extends Control implements Container
{
    private $pages = array();

    public function __construct($label, Point $topLeftCorner, Dimension $dimension/*$style = Control::DEFAULT_STYLE, $param = 0, $tabIndex = 0*/)
    {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->type = TabControl;
    }

    public function addPage()
    {
        $tabPageControlID = wb_create_items($this->controlID, "sample");
        $tabPageControlID = wb_create_items($this->controlID, "sample2");
        $tabPageControlID = wb_create_items($this->controlID, "sample3");

        //$this->pages[] = $page;

        //$page->setParentControl($this);

        //$this->childControls[$page->id] = $page;
    }

    public function add(Component $control)
    {
    }

    /**
     * This method removes a control from the parent.
     *
     * @param Component $control
     */
    public function remove(Component $control)
    {
    }
}