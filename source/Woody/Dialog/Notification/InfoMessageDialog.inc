<?php

namespace Woody\Dialog\Notification;

use \Woody\Components\Windows\AbstractWindow;

class InfoMessageDialog extends MessageDialog {

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, $text, AbstractWindow $parentWindow = null) {
        parent::__construct(WBC_INFO, $title, $text, $parentWindow);
    }
}