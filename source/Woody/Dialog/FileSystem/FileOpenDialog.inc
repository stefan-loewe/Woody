<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FileOpenDialog extends FileSystemDialog {

    private $path       = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title,
                                AbstractWindow $parentWindow = null,
                                $path = null,
                                $filename = null,
                                $filter = array()) {
        parent::__construct($title, $parentWindow);
        $this->path     = $path;
        $this->filename = $filename;
        $this->filter   = $filter;
    }

    function open() {
        $this->selection = wb_sys_dlg_open($this->window->getControlID(),
                                            $this->title,
                                            $this->filter,
                                            $this->path,
                                            $this->filename);
    }

    function getSelection() {
        $this->selection = trim($this->selection);

        return (strlen($this->selection) === 0) ? null : $this->selection;
    }
}