<?php

namespace Woody\Utils\Geom;

//merge this with Utils in ReingoldTilford
//see if controls in frame, tab-control can be pseudo-added to these, i.e. add them to frame, by adding them to the frame logically but to the window physically
//then, if tab is selected, hide controls from other tab ... same with frame ... composite pattern

class Point
{
    /**
     * the x-coordinate of the point
     *
     * @var int
     */
    private $x      = null;

    /**
     * the y-coordinate of the point
     *
     * @var int
     */
    private $y      = null;

    public function  __construct($x, $y)
    {
        $this->x    = $x;
        $this->y    = $y;
    }

    public function __get($memberName)
    {
        return $this->$memberName;
    }

    public function setX($x)
    {
        return new Point($x, $this->y);
    }

    public function setY($y)
    {
        return new Point($this->x, $y);
    }

    public function moveBy($xOffset, $yOffset)
    {
        return new Point($this->x + $xOffset, $this->y + $yOffset);
    }

    public function equals($other)
    {
        if($other instanceof self)
            return $this->x === $other->x && $this->y === $other->y;

        else
            return false;
    }

    public function __toString()
    {
        return 'x: '.$this->x.' / y: '.$this->y;
    }
}