<?php

namespace Woody\Utils\Geom;

class Dimension
{
    private $width  = null;

    private $height = null;

    public function  __construct($width, $height)
    {
        $this->width    = $width;
        $this->height   = $height;
    }

    public function __get($memberName)
    {
        return $this->$memberName;
    }

    public function setWidth($width)
    {
        return new Dimension($width, $this->height);
    }

    public function setHeight($height)
    {
        return new Dimension($this->width, $height);
    }

    public function resizeTo($newHeight, $newWidth)
    {
        $newDimension = $this->setWidth($newWidth);

        return $newDimension->setHeight($newHeight);
    }

    public function resizeBy($xOffset, $yOffset)
    {
        $newDimension = $this->setWidth($this->width + $xOffset);

        return $newDimension->setHeight($this->height + $yOffset);
    }

    public function equals($other)
    {
        if($other instanceof self)
            return $this->width === $other->width && $this->height === $other->height;

        else
            return false;
    }
}