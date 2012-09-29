<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FileOpenDialog extends FileSystemDialog {
  /**
   * the collection of file filters for this dialog
   *
   * @var \Woody\Dialog\FileSystem\FileFilters
   */
  protected $filters = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct($title, AbstractWindow $parentWindow = null, $path = null, FileFilters $filters = null) {
    parent::__construct($title, $parentWindow, $path);
    $this->filters = $filters;
  }

  public function open() {
    $this->selection[0] = wb_sys_dlg_open(
      $this->window === null ? null : $this->window->getControlID(),
      $this->title, $this->filters === null ? '' : $this->filters->toArray(),
      $this->path,
      null,
      null
    );
  }
}