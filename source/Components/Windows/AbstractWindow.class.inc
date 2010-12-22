<?php

namespace Woody\Components\Windows;

use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;
use Woody\System\System;

abstract class AbstractWindow
{
    private $type               = null;

    private $windowID           = null;

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


	private $parentWindow       = null;

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

        $this->topLeftCorner    = new Point($topLeftCorner->getX(), $topLeftCorner->getY());

        $this->dimension        = new Dimension($dimension->getWidth(), $dimension->getHeight());

        $this->style            = $style;

        $this->param            = $param;

        $this->parentWindow     = $parentWindow;

        // set the default event handler for closing the window
		//$this->setEventHandler(self::getDefaultCallback());
	}

    public function create()
    {
        $this->windowID = wb_create_window($this->parentWindow === null ? null : $this->parentWindow->getWindowID(),
                                               $this->type,
                                               $this->label,
                                               $this->topLeftCorner->getX(),
                                               $this->topLeftCorner->getY(),
                                               $this->dimension->getWidth(),
                                               $this->dimension->getHeight(),
                                               $this->style | WBC_TASKBAR,
                                               $this->param);

		return $this;
    }

	public function destroy()
	{
		wb_destroy_window($this->windowID);
	}

    public function startEventHandler()
    {
		wb_set_handler($this->windowID, 'str_replace');

wb_main_loop();
    }

    public function getPosition()
    {
        $position = wb_get_position($this->windowID);

        $this->topLeftCorner->setX($position[0])
                ->setY($position[1]);

        return new Point($this->topLeftCorner->getX(), $this->topLeftCorner->getY());
    }

    public function getDimension()
    {
        $size = wb_get_size($this->windowID);

        $this->dimension->setWidth($size[0])
                ->setHeight($size[1]);

        return new Dimension($this->dimension->getWidth(), $this->dimension->getHeight());
    }

    public function moveBy($xOffset, $yOffset)
    {
        $this->topLeftCorner->setX($this->topLeftCorner->getX() + $xOffset)
                ->setY($this->topLeftCorner->getY() + $yOffset);

        $this->move();

        return $this;
    }

    public function moveTo($x, $y)
    {
        $this->topLeftCorner->setX($x)
                ->setY($y);

        $this->move();

        return $this;
    }

    private function move()
    {
        $this->topLeftCorner->setX(max(System::WIN_MIN_TOP, $this->topLeftCorner->getX()))
                ->setY(max(System::WIN_MIN_LEFT, $this->topLeftCorner->getY()));

        wb_set_position($this->windowID, $this->topLeftCorner->getX(), $this->topLeftCorner->getY());

        return $this;
    }

    public function resizeBy($xOffset, $yOffset)
    {
        $this->dimension->setWidth($this->dimension->getWidth() + $xOffset)
                ->setHeight($this->dimension->getHeight() + $yOffset);

        $this->resize();

        return $this;
    }

    public function resizeTo($width, $height)
    {
        $this->dimension->setWidth($width)
                ->setHeight($height);

        $this->resize();

        return $this;
    }

    private function resize()
    {
        $this->dimension->setWidth(max(System::WIN_MIN_WIDTH, $this->dimension->getWidth()))
                ->setHeight(max(System::WIN_MIN_HEIGHT, $this->dimension->getHeight()));

        wb_set_size($this->windowID, $this->dimension->getWidth(), $this->dimension->getHeight());

        return $this;
    }
}