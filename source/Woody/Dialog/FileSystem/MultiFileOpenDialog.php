<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class MultiFileOpenDialog extends FileOpenDialog {
  public function __construct($title, AbstractWindow $parentWindow = null, $path = null, FileFilters $filters = null) {
    parent::__construct($title, $parentWindow, $path, $filters);
  }

  public function open() {
    $this->selection = wb_sys_dlg_open(
      $this->window === null ? null : $this->window->getControlID(),
      $this->title,
      $this->filters === null ? '' : $this->filters->toArray(),
      $this->path,
      null,
      WBC_MULTISELECT
    );
  }

  public function getSelection() {
    $selection = (count($this->selection) === 0) ? array() : $this->selection;

    return new \ArrayObject($selection);
  }
}