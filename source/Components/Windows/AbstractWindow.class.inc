<?php

namespace Woody\Components\Windows;

use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;
use Woody\System\System;

abstract class AbstractWindow
{
    private $type               = null;


    /**
     * the internal winbinder control id of this window
     *
     * @var int
     */
    private $controlID           = null;

    /**
     * the label of the window control
     *
     * @var string
     */
    private $label              = null;

    /**
     * the top left corner of the window
     *
     * @var Woody\Utils\Misc\Point
     */
    private $topLeftCorner      = null;

    /**
     * the dimension of the window
     *
     * @var Woody\Utils\Misc\Dimension
     */
    private $dimension          = null;

    /**
     * the internal winbinder control id of the parent window of this window
     *
     * @var int
     */
    private $parentID           = null;

    // @TODO think how to handle this ... Decorator?
    private $param              = null;
    private $style              = null;
/*
    const CENTERED              = WBC_CENTER;

    const TITLE_BAR_HEIGHT      = 30;

    const BORDER_WIDTH          = 2;
*/
    public function __construct($type, $id, $label, Point $topLeftCorner, Dimension $dimension, $style, $param, Window $parentWindow = null)
    {
        //todo: type via decorators???
        $this->type             = $type;

        $this->id               = $id;

        $this->label            = $label;

        $this->topLeftCorner    = new Point($topLeftCorner->x, $topLeftCorner->y);

        $this->dimension        = new Dimension($dimension->width, $dimension->height);

        $this->style            = $style;

        $this->param            = $param;

        $this->parentID     = $parentWindow;

        // set the default event handler for closing the window
        //$this->setEventHandler(self::getDefaultCallback());
    }

    public function getControlID()
    {
        return $this->controlID;
    }

    public function add($control)
    {
        $control->create($this);
    }

    public function create()
    {
        $this->controlID = wb_create_window($this->parentID === null ? null : $this->parentID->getWindowID(),
                                               $this->type,
                                               $this->label,
                                               $this->topLeftCorner->x,
                                               $this->topLeftCorner->y,
                                               $this->dimension->width,
                                               $this->dimension->height,
                                               $this->style | WBC_TASKBAR,
                                               $this->param);

        return $this;
    }

    public function destroy()
    {
        wb_destroy_window($this->controlID);
    }

    public function startEventHandler()
    {
        wb_set_handler($this->controlID, 'str_replace');

        wb_main_loop();
    }

    public function getPosition()
    {
        return new Point($this->topLeftCorner->x, $this->topLeftCorner->y);
    }

    public function getDimension()
    {
        $size = wb_get_size($this->controlID);

        $this->dimension->setWidth($size[0])
                ->setHeight($size[1]);

        return new Dimension($this->dimension->width, $this->dimension->height);
    }

    public function moveBy($xOffset, $yOffset)
    {
        $this->topLeftCorner = $this->topLeftCorner->moveBy($xOffset, $yOffset);

        $this->move();

        return $this;
    }

    public function moveTo(Point $topLeftCorner)
    {
        $this->topLeftCorner = new Point($topLeftCorner->x, $topLeftCorner->y);

        $this->move();

        return $this;
    }

    protected function move()
    {
        $x = max(System::WIN_MIN_TOP, $this->topLeftCorner->x);
        $y = max(System::WIN_MIN_LEFT, $this->topLeftCorner->y);
        $this->topLeftCorner = new Point($x, $y);

        wb_set_position($this->controlID, $this->topLeftCorner->x, $this->topLeftCorner->y);

        return $this;
    }

    public function resizeBy($xOffset, $yOffset)
    {
        $this->dimension = $this->dimension->resizeBy($xOffset, $yOffset);

        return $this->resize();
    }

    public function resizeTo(Dimension $dimension)
    {
        $this->dimension = new Dimension($dimension->width, $dimension->height);

        return $this->resize();
    }

    protected function resize()
    {
        $width  = (max(System::WIN_MIN_WIDTH, $this->dimension->width));
        $height = (max(System::WIN_MIN_HEIGHT, $this->dimension->height));

        $this->dimension = new Dimension($width, $height);

        wb_set_size($this->controlID, $this->dimension->width, $this->dimension->height);

        return $this;
    }
}