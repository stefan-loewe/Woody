<?php

namespace Woody\Components\Controls;

/**
 * Description of Frame
 *
 * @author Stefan
 */
class Frame
{
    /**
     * the child controls of this frame
     *
     * @var SplObjectStorage
     */
    protected $children = null;

    public function __construct()
    {
        $this->children = new \SplObjectStorage();
    }

    public function create($parent)
    {
        $this->parent       = $parent;
        $this->controlID    = wb_create_control
                            (
                                $this->parent->getControlID(),
                                Frame,
                                'my new frame',
                                10,
                                10,
                                160,
                                100,
                                101010101// , self::UniqueID
                                //$this->style,
                                //$this->param,
                                //$this->tabIndex
                            );var_dump('created');
    }

    public function add($control)
    {
        // creation has to be delayed until adding control to respective scope (window, frame, etc.)
        // maybe add flag to denote, if already created once, to avoid duplication ...
        // c = new Control(); frame->add(c); frame->add(c); ...
        $control->create($this);

        $this->children[$control] = TRUE;

        return $this;
    }

    public function removeControl(ComponentControl $control)
    {
        //
    }
}