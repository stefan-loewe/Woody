<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

class WarningMessageDialog extends MessageDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_WARNING, $title, $text, $parentWindow);
    }
}