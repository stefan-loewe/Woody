<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Components\Container;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Frame extends Control implements Container {
    /**
     * the child controls of this frame
     *
     * @var SplObjectStorage
     */
    protected $children = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param string $label the label of the frame
     * @param Point $topLeftCorner the top left corner of the frame
     * @param Dimension $dimension the dimension of the frame
     */
    public function __construct($label, Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($label, $topLeftCorner, $dimension);

        $this->type     = Frame;

        $this->children = new \SplObjectStorage();
    }

    /**
     * This method adds a control to the frame. Calling this method twice with the same object behaves like moving the control from one parent to the other.
     *
     * The controls are only added logically to the frame, physically, they are added to the window to which the frame belongs.
     * Furthermore, creating of the control (in respect to winbinder) is done only here, and not in the constructor, as the parent element has to be known.
     *
     * @param Component $control
     * @return Frame
     */
    public function add(Component $control) {
        if($control->parent !== null) {
            $this->remove($control);
        }

        $control->create($this);

        $this->children[$control] = $control->controlID;

        return $this;
    }

    /**
     * This method removes a control from the parent.
     *
     * @param Component $control
     */
    public function remove(Component $control) {
        if($this->children->offsetExists($control)) {
            $this->children->offsetUnset($control);

            wb_destroy_control($control->controlID);

            $control->controlID = null;
            $control->parent    = null;
        }

        return $this;
    }
}