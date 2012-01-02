<?php

namespace Woody\Dialog\PopUp;

use \Woody\Components\Windows\AbstractWindow;

class YesNoCancelConfirmationDialog extends PopUpDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_YESNOCANCEL, $title, $text, $parentWindow);
    }

    public function yes() {
        return $this->state === TRUE;
    }

    public function no() {
        return $this->state === 0;
    }

    public function cancel() {
        return $this->state === FALSE;
    }
}