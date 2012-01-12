<?php

namespace Woody\Dialog\PopUp;

use \Woody\Components\Windows\AbstractWindow;

class OkCancelConfirmationDialog extends PopUpDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_OKCANCEL, $title, $text, $parentWindow);
    }

    /**
     * This method returns true if the ok button was clicked.
     *
     * @return boolean true, if the ok button was clicked, else false
     */
    public function ok() {
        return $this->state === TRUE;
    }

    /**
     * This method returns true if the cancel button was clicked.
     *
     * @return boolean true, if the cancel button was clicked, else false
     */
    public function cancel() {
        return $this->state === FALSE;
    }
}