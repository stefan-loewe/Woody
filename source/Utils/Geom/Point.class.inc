<?php

namespace Woody\Utils\Geom;

class Point
{
    private $x      = null;

    private $y      = null;

    public function  __construct($x, $y)
    {
        $this->x        = $x;
        $this->y        = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    public function equals($other)
    {
        if($other instanceof self)
            return $this->x === $other->x && $this->x === $other->x;

        else
            return false;
    }
}