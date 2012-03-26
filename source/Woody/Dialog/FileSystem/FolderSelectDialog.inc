<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FolderSelectDialog extends FileSystemDialog {

    private $path       = null;
    private $filename   = null;
    private $filter     = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title, AbstractWindow $parentWindow = null, $path = null) {
        parent::__construct($title, $parentWindow);
        $this->path = $path;
    }

    function open() {
        $this->selection = wb_sys_dlg_path($this->window->getControlID(),
                                            $this->title,
                                            $this->path);
    }

    function getSelection() {
        return $this->selection;
    }
}