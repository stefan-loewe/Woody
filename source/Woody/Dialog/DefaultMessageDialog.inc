<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

class DefaultMessageDialog extends MessageDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_OK, $title, $text, $parentWindow);
    }
}