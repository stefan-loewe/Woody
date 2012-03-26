<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FileSaveDialog extends FileSystemDialog {

    private $path           = null;
    private $filename       = null;
    private $filter         = null;
    private $defaultFilter  = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, AbstractWindow $parentWindow = null, $path = null, $filename = null, $filter = array(), $defaultFilter = null) {
        parent::__construct($title, $parentWindow);
        $this->path             = $path;
        $this->filename         = $filename;
        $this->filter           = $filter;
        $this->defaultFilter    = $defaultFilter;
    }

    function open() {

    }

    function getSelection() {

    }
}