<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FileOpenDialog extends FileSystemDialog {
    /**
     * the default path which to open
     */
    private $path       = null;

    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title,
                                AbstractWindow $parentWindow = null,
                                $path = null,
                                FileFilters $filters = null) {
        parent::__construct($title, $parentWindow);
        $this->path     = $path;
        $this->filters  = $filters;
    }

    function open() {
        $this->selection = wb_sys_dlg_open($this->window->getControlID(),
                                            $this->title,
                                            $this->filters === null ? '' : $this->filters->toArray(),
                                            $this->path);
    }

    function getSelection() {
        $this->selection = trim($this->selection);

        return (strlen($this->selection) === 0) ? null : $this->selection;
    }
}