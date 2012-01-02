<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

class YesNoConfirmationDialog extends ConfirmationDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_YESNO, $title, $text, $parentWindow);
    }

    public function yes() {
        return $this->state === TRUE;
    }

    public function no() {
        return $this->state === 0;
    }
}