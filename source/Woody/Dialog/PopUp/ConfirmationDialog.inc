<?php

namespace Woody\Dialog\PopUp;

use \Woody\Components\Windows\AbstractWindow;

abstract class ConfirmationDialog extends PopUpDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($style, $title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct($style, $title, $text, $parentWindow);
    }
}