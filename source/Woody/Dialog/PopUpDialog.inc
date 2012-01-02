<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

abstract class PopUpDialog {

    protected $title    = null;
    protected $text     = null;
    protected $window   = null;
    protected $style    = null;
    /**
     * the state of the dialog
     *
     * @var int
     */
    protected $state = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
        $this->style    = $style;
        $this->title    = $title;
        $this->text     = $text;
        $this->window   = $parentWindow;
    }

    public function open() {
        $this->state = wb_message_box($this->window, $this->text, $this->title, $this->style);
    }
}