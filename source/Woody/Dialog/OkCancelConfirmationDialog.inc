<?php

namespace Woody\Dialog;

use \Woody\Components\Windows\AbstractWindow;

class ConfirmationDialog extends PopUpDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_OKCANCEL, $title, $text, $parentWindow);
    }

    public function ok() {
        return $this->state === TRUE;
    }

    public function cancel() {
        return $this->state === FALSE;
    }
}