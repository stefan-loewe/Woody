<?php

namespace Woody\Components\Controls;

use Woody\Components\Component;
use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;

abstract class Control extends Component
{
    public function __construct($label, Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct($label, $topLeftCorner, $dimension);
    }

    protected function create(Component $parent)
    {
        $this->parent = $parent;

        $absoluteTopLeftCorner = $this->calculateAbsoluteTopLeftCorner();

        $this->controlID    = wb_create_control
                            (
                                $parent->getWindow()->getControlID(),
                                $this->type,
                                $this->value,
                                $absoluteTopLeftCorner->x,
                                $absoluteTopLeftCorner->y,
                                $this->dimension->width,
                                $this->dimension->height,
                                $this->id,
                                0,
                                0,
                                $this->id
                            );
    }

    private function calculateAbsoluteTopLeftCorner()
    {
        $absTopLeftCorner = $this->topLeftCorner;

        $parent = $this;
        while(($parent = $parent->parent) != null)
        {
            if($parent instanceof Frame)
                $absTopLeftCorner = $absTopLeftCorner->moveBy($parent->topLeftCorner->x, $parent->topLeftCorner->y);
        }

        return $absTopLeftCorner;
    }
}