<?php

namespace ws\loewe\Woody\Dialog\FileSystem;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;

class FolderSelectDialog extends FileSystemDialog {
  public function __construct($title, AbstractWindow $parentWindow = null, $path = null) {
    parent::__construct($title, $parentWindow, $path);
  }

  public function open() {
    $this->selection[0] = wb_sys_dlg_path(
      $this->window === null ? null : $this->window->getControlID(),
      $this->title,
      $this->path
    );
  }
}