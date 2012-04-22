<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

class FileSaveDialog extends FileSystemDialog {
  /**
   * the collection of file filters for this dialog
   *
   * @var \Woody\Dialog\FileSystem\FileFilters
   */
  private $filters = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct($title, AbstractWindow $parentWindow = null, $path = null, $filters = null) {
    parent::__construct($title, $parentWindow, $path);
    $this->filters = $filters;
  }

  function open() {
    $this->selection[0] = wb_sys_dlg_save(
      $this->window === null ? null : $this->window->getControlID(),
      $this->title,
      $this->filters === null ? '' : $this->filters->toArray(),
      $this->path,
      null,
      null
    );
  }
}