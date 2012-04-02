<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

class ErrorMessageDialog extends MessageDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_STOP, $title, $text, $parentWindow);
    }
}