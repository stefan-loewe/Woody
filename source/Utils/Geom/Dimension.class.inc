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

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function equals($other)
    {
        if($other instanceof self)
            return $this->width === $other->width && $this->height === $other->height;

        else
            return false;
    }
}