<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FolderSelectDialog extends FileSystemDialog {
    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title,
                                AbstractWindow $parentWindow = null,
                                $path = null) {
        parent::__construct($title, $parentWindow, $path);
    }

    function open() {
        $this->selection[0] = wb_sys_dlg_path($this->window === null ? null : $this->window->getControlID(),
                                                $this->title,
                                                $this->path);
    }

    function getSelection() {
        return $this->selection;
    }
}