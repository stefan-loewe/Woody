<?php

namespace Woody\Components\Windows;

use Woody\Utils\Misc\Rectangle;

abstract class AbstractWindow
{
    private $type           = null;

    private $windowID       = null;

    /**
     * the label of the window control
	 *
	 * @var string
	 */
	private $label          = null;

    /**
     * the bounding rectange of the window
     *
     * @var Rectangle
     */
	private $rectangle      = null;

    /**
     * the bounding rectange of the window
     *
     * @var Rectangle
     */
	private $parentWindow   = null;

	// @TODO think how to handle this ... Decorator?
    private $param   = null;
    private $style   = null;
/*
    const CENTERED              = WBC_CENTER;

    const TITLE_BAR_HEIGHT      = 30;

    const BORDER_WIDTH          = 2;
*/
	public function __construct($type, $id, $label, Rectangle $rectangle, $style, $param, Window $parentWindow = null)
	{
        //todo: type via decorators???
        $this->type         = $type;

        $this->id           = $id;

        $this->label        = $label;

        $this->rectangle    = new Rectangle($rectangle->getX(), $rectangle->getY(), $rectangle->getWidth(), $rectangle->getHeight());

        $this->style        = $style;

        $this->param        = $param;

        $this->parentWindow = $parentWindow;

        // set the default event handler for closing the window
		//$this->setEventHandler(self::getDefaultCallback());
	}

    public function create()
    {
        $this->windowID = wb_create_window($this->parentWindow === null ? null : $this->parentWindow->getWindowID(),
                                               $this->type,
                                               $this->label,
                                               $this->rectangle->getX(),
                                               $this->rectangle->getY(),
                                               $this->rectangle->getWidth(),
                                               $this->rectangle->getHeight(),
                                               $this->style,
                                               $this->param);

		return $this;
    }

	public function destroy()
	{
		wb_destroy_window($this->controlID);

        parent::destroy();
	}

    public function initialize()
    {
		//wb_set_handler($this->windowID, 'globalWinBinderEventHandler');

//wb_main_loop();

		return $this;
    }

    public function getPosition()
    {
        return wb_get_position($this->windowID);
    }

    public function moveBy($xOffset, $yOffset)
    {
        $this->rectangle->moveBy($xOffset, $yOffset);

        return $this->move();
    }

    public function moveTo($x, $y)
    {
        $this->rectangle->moveTo($x, $y);

        return $this->move();
    }

    private function move()
    {
        wb_set_position($this->windowID, $this->rectangle->getX(), $this->rectangle->getY());

        return $this;
    }

    public function resizeBy($xOffset, $yOffset)
    {
        $this->rectangle->resizeBy($xOffset, $yOffset);

        return $this->resize();
    }

    public function resizeTo($x, $y)
    {
        $this->rectangle->resizeTo($x, $y);

        return $this->resize();
    }

    private function resize()
    {
        wb_set_size($this->windowID, $this->rectangle->getWidth(), $this->rectangle->getHeight());

        return $this;
    }
}