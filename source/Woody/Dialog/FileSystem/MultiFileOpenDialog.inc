<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class MultiFileOpenDialog extends FileOpenDialog {
    /**
     * This method acts as the constructor of the class.
     */
    public function __construct($title,
                                AbstractWindow $parentWindow = null,
                                $path = null,
                                FileFilters $filters = null) {
        parent::__construct($title, $parentWindow, $path, $filters);
    }

    function open() {
        $this->selection = wb_sys_dlg_open($this->window === null ? null : $this->window->getControlID(),
                                            $this->title,
                                            $this->filters === null ? '' : $this->filters->toArray(),
                                            $this->path,
                                            null,
                                            WBC_MULTISELECT);

        var_dump($this->selection);
    }

    /**
     * This method returns the collection of full paths to the selected file system objects, or an empty collection if nothing was selected.
     *
     * @return \ArrayObject the full paths to the selected file system objects, or an empty collection if nothing was selected.
     */
    function getSelection() {
        $selection = (count($this->selection) === 0) ? array() : $this->selection;

        return new \ArrayObject($selection);
    }
}