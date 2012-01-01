<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Util\ImageResource;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ImageButton extends Button {
    /**
     * the image resource the image button is associated with
     *
     * @var \Woody\Utils\ImageResource
     */
    private $imageResource = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param \Woody\Utils\ImageResource $imageResource the image resource the image button is associated with
     * @param Point $topLeftCorner the top left corner of the image
     * @param Dimension $dimension the dimension of the image
     */
    public function __construct(ImageResource $imageResource, Point $topLeftCorner, Dimension $dimension) {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->imageResource    = $imageResource;
    }

    /**
     * This method creates the image button and sets the image afterwards.
     *
     * @param Component $parent the parent component of the control
     */
    protected function create(Component $parent) {
        parent::create($parent);

        $this->setImage();
    }

    /**
     * This method sets the image of the image button.
     *
     * @return \Woody\Components\Controls\ImageButton $this
     */
    private function setImage() {
        $bitmap = $this->imageResource->getBitmap();

        wb_set_image($this->controlID, $bitmap);
        wb_destroy_image($bitmap);

        return $this;
    }
}