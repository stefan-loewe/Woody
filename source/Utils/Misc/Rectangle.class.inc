<?php

namespace Woody\Utils\Misc;

class Rectangle
{
    private $x      = null;

    private $y      = null;

    private $width  = null;

    private $height = null;

    public function  __construct($x, $y, $width, $height)
    {
        $this->x        = $x;
        $this->y        = $y;
        $this->width    = $width;
        $this->height   = $height;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function moveBy($xOffset, $yOffset)
    {
        $this->x += $xOffset;
        $this->y += $yOffset;

        return $this;
    }

    public function moveTo($x, $y)
    {
        $this->x = $x;
        $this->y = $y;

        return $this;
    }
}