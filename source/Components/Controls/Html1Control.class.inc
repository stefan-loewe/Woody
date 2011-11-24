<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;

class HtmlControl extends Control
{
    public function __construct($url, Point $topLeftCorner, Dimension $dimension/*$style = Control::DEFAULT_STYLE, $param = 0, $tabIndex = 0*/)
    {
        parent::__construct($url, $topLeftCorner, $dimension);

        $this->type = HTMLControl;
    }
/*
    public function destroy()
    {
        FotoImportApplication::getHTMLControlServer()->unregister($this);
    }
*/
    public function create(Component $parent)
    {
        parent::create($parent);

        $this->setUrl($this->value);
    }

    public function getUrl()
    {
        return $this->value;
    }

    public function setUrl($url)
    {
        $this->value = $url;

        wb_set_location($this->controlID, $this->value);
    }
}