<?php

namespace Woody\Components;

use Woody\Components\Windows\AbstractWindow;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

use Woody\System\System;

/**
 * Description of Component
 *
 * @author Stefan
 */
abstract class Component implements IComponent {
    /**
     * the internal winbinder control id of this component
     *
     * @var int
     */
    protected $controlID        = null;

    /**
     * the logical id of this component
     *
     * @var int
     */
    protected $id               = null;

    /**
     * the type of the component, i.e. the winbinder constant representing the control type
     *
     * @var int
     */
    protected $type             = null;

    /**
     * the top left corner of the component
     *
     * @var Woody\Utils\Misc\Point
     */
    protected $topLeftCorner    = null;

    /**
     * the dimension of the component
     *
     * @var Woody\Utils\Misc\Dimension
     */
    protected $dimension        = null;

    /**
     * the value (e.g. value of an EditBox, the label of a Frame, the selection of a ListBox) of the control
     *
     * @var string
     */
    protected $value            = null;

    /**
     * the parent container of this window
     *
     * @var Container (to-be-defined interface)
     */
    protected $parent           = null;

    // @TODO how to handle param and style ?!?!this ... Decorator?
    protected $param            = 0;
    protected $style            = 0;

    /**
     * static internal integer, that is used as id generator for creating components, starting at 1024 not to interfer with special control ids (e.g. 8 is window close)
     *
     * @var int
     */
    private static $IDENTIFIER  = 1024;

    public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
        $this->id               = self::getUniqueID();

        $this->value            = $value;

        $this->topLeftCorner    = new Point($topLeftCorner->x, $topLeftCorner->y);

        $this->dimension        = new Dimension($dimension->width, $dimension->height);
    }

    /**
     * This method return an unique identifier for each control
     *
     * The fact that this method is public is an implementation detail - the Timer class needs this functionality, too. Do not cal this method.
     *
     * @return int
     */
    public static function getUniqueID() {
        return ++self::$IDENTIFIER;
    }

    /**
     * This method creates a component physically as a winbinder control.
     *
     * Do not call this method manually, it is called by Woody\Components\Container::add only. It is an implementation sid-effect that it is public.
     *
     * @param Component $parent the parent component of this component
     * @return $this
     */
    abstract protected function create(Component $parent);

    /**
     * This method returns the winbinder control id of the component.
     *
     * @return int
     */
    public function getControlID() {
        return $this->controlID;
    }

    /**
     * This method returns the id of the component.
     *
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * This method returns the top left corner of the component.
     *
     * @return Woody\Utils\Geom\Point the top left corner of the component.
     */
    public function getPosition() {
        return new Point($this->topLeftCorner->x, $this->topLeftCorner->y);
    }

    /**
     * This method returns the dimension of the top left corner of the component.
     *
     * @return Woody\Utils\Geom\Dimension the dimension of the control
     */
    public function getDimension() {
        return new Dimension($this->dimension->width, $this->dimension->height);
    }

    /**
     * This method moves the component by an offset, given as dimension.
     *
     * @param \Utils\Geom\Dimension $dimension the dimension by which this point shall be moved by
     * @return Component $this
     */
    public function moveBy(Dimension $dimension) {
        return $this->move($this->topLeftCorner->moveBy($dimension));
    }

    /**
     * This method moves the top left corner of the component to the given point.
     *
     * @param Point $topLeftCorner the new point of the top left corner
     * @return Component $this
     */
    public function moveTo(Point $topLeftCorner) {
        return $this->move($topLeftCorner);
    }

    /**
     * This method moves the top left corner of the component to the given point, respecting the bounds of the screen.
     *
     * @param Point $topLeftCorner
     * @return Component $this
     * @todo this is different for windows & controls
     */
    protected function move(Point $topLeftCorner) {
        $x = max(System::WIN_MIN_TOP, $topLeftCorner->x);
        $y = max(System::WIN_MIN_LEFT, $topLeftCorner->y);

        wb_set_position($this->controlID, $x, $y);

        $this->topLeftCorner = new Point($x, $y);

        return $this;
    }

    /**
     * This method resizes the component by the offset given as a dimension.
     *
     * @param Dimension $dimension the offset by which the component has to be resized
     * @return Component $this
     */
    public function resizeBy(Dimension $dimension) {
        return $this->resize($this->dimension->resizeBy($dimension));
    }

    /**
     * This method resizes the component to the given dimension.
     *
     * @param Dimension $dimension the new dimension of the component.
     * @return Component $this
     */
    public function resizeTo(Dimension $dimension) {
        return $this->resize($dimension);
    }

    /**
     * This method resizes the component to the given dimension, respecting the minimal size of a window.
     *
     * @param Dimension $dimension the new dimension of the component.
     * @return Component $this
     */
    protected function resize(Dimension $dimension) {
        $width  = (max(System::WIN_MIN_WIDTH, $dimension->width));
        $height = (max(System::WIN_MIN_HEIGHT, $dimension->height));

        wb_set_size($this->controlID, $width, $height);

        $this->dimension = new Dimension($width, $height);

        return $this;
    }

    /**
     * This method returns the window to which this component is attached to, or null if none was found.
     *
     * @return AbstractWindow the window to which this component is attached to
     */
    protected function getWindow() {
        $currentParent = $this;

        while($currentParent != null) {
            if($currentParent instanceof AbstractWindow) {
                return $currentParent;
            }

            $currentParent = $currentParent->parent;
        }

        return null;
    }
}