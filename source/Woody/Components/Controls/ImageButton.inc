<?php

namespace Woody\Components\Controls;

use \Woody\Components\IComponent;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ImageButton implements IComponent {
    /**
     * the file name of the image of the image button
     *
     * @var string
     */
    private $imageFileName  = null;

    /**
     *
     * @var \Woody\Components\Controls\PushButton
     */
    private $button         = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param \Woody\Components\Controls\PushButton the button that should be decorated by an image
     * @param string $imageFileName the image source file of image button
     */
    public function __construct(PushButton $button, $imageFileName) {
        $this->button           = $button;

        $this->imageFileName    = $imageFileName;
    }

    /**
     * This method creates the iage button and sets the image afterwards.
     *
     * @param Component $parent the parent component of the control
     */
    protected function create(Component $parent) {
        parent::create($parent);
this won't work, create is protected, and that's good, actually
        $this->setImage();
    }

    /**
     * This method sets the image of the image button.
     *
     * @return \Woody\Components\Controls\ImageButton $this
     */
    private function setImage() {
        $dib = FreeImage_Load(self::isJpeg() ? FIF_JPEG : FIF_BMP, $this->imageFileName, 0);

        $bmp = wb_create_image($this->dimension->width,
                                $this->dimension->height,
                                FreeImage_GetInfoHeader($dib),
                                FreeImage_GetBits($dib));

        wb_set_image($this->controlID, $bmp);

        // clean up
        FreeImage_Unload($dib);
        wb_destroy_image($bmp);

        return $this;
    }

    /**
     * This method determines if the image is a jpeg or not.
     *
     * @return boolean true, if the image is a jpeg, else false
     */
    private static function isJpeg() {
        return substr($this->imageFileName, -4) === '.jpg';
    }

    /**
     * This method returns the winbinder control id of the component.
     *
     * @return int
     */
    public function getControlID() {
        return $this->button->getControlID();
    }

    /**
     * This method returns the id of the component.
     *
     * @return int
     */
    public function getID() {
        return $this->button->getID();
    }

    /**
     * This method returns the top left corner of the component.
     *
     * @return Woody\Utils\Geom\Point the top left corner of the component.
     */
    public function getPosition() {
        return $this->button->getPosition();
    }

    /**
     * This method returns the dimension of the top left corner of the component.
     *
     * @return Woody\Utils\Geom\Dimension the dimension of the control
     */
    public function getDimension() {
        return $this->button->getDimension();
    }

    /**
     * This method moves the component by an offset, given as dimension.
     *
     * @param \Utils\Geom\Dimension $dimension the dimension by which this point shall be moved by
     * @return Component $this
     */
    public function moveBy(Dimension $dimension) {
        return $this->button->move($this->button->getPosition()->moveBy($dimension));
    }

    /**
     * This method moves the top left corner of the component to the given point.
     *
     * @param Point $topLeftCorner the new point of the top left corner
     * @return Component $this
     */
    public function moveTo(Point $topLeftCorner) {
        return $this->button->move($topLeftCorner);
    }

    /**
     * This method resizes the component by the offset given as a dimension.
     *
     * @param Dimension $dimension the offset by which the component has to be resized
     * @return Component $this
     */
    public function resizeBy(Dimension $dimension) {
        return $this->button->resize($this->button->getDimension()->resizeBy($dimension));
    }

    /**
     * This method resizes the component to the given dimension.
     *
     * @param Dimension $dimension the new dimension of the component.
     * @return Component $this
     */
    public function resizeTo(Dimension $dimension) {
        return $this->button->resize($dimension);
    }

}