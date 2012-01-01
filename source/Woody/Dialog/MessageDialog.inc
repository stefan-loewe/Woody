<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

class MessageDialog extends PopUpDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct($title, $text, $parentWindow);
        $this->style = $style;
    }
}