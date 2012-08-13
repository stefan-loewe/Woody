<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

abstract class FileSystemDialog {
  /**
   * the title of the dialog
   *
   * @var string
   */
  protected $title = null;

  /**
   * the window the dialog belongs to, may be null
   *
   * @var \Woody\Components\Windows\AbstractWindow
   */
  protected $window = null;

  /**
   * the default path which to open, must be delimitet by slash, e.g. "C:\path\to\dir\"
   */
  protected $path = null;

  /**
   * the collection of full paths to the file system objects the user selected
   *
   * @var array the collection of full paths to the file system objects the user selected
   */
  protected $selection = array();

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct($title, AbstractWindow $parentWindow = null, $path = null) {
    $this->title  = $title;
    $this->window = $parentWindow;
    $this->path   = $path;
  }

  /**
   * This method opens the respective dialog.
   */
  abstract function open();

  /**
   * This method returns the full path to the selected file system object, or null if nothing was selected.
   *
   * @return string the full path to the selected file system object, or null if nothing was selected
   */
  public function getSelection() {
    return (count($this->selection) === 0) ? null : trim($this->selection[0]);
  }
}