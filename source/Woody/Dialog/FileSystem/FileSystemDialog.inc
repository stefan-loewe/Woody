<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

abstract class FileSystemDialog {
    /**
     * the title of the dialog
     *
     * @var string
     */
    protected $title        = null;

    /**
     * the window the dialog belongs to, may be null
     *
     * @var \Woody\Components\Windows\AbstractWindow
     */
    protected $window       = null;

    /**
     * the selection of the user when closing the dialog
     *
     * @var string the selection, which may be null when the user pressed "cancel"
     */
    protected $selection    = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, AbstractWindow $parentWindow = null) {
        $this->title    = $title;
        $this->window   = $parentWindow;
    }

    /**
     * This method opens the dialog.
     */
    abstract function open();

    /**
     * This method returns the full path to the selected file system object.
     */
    abstract function getSelection();
}