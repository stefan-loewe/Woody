<?php

namespace Woody\Dialog\FileSystem;

class FileFilters {
  /**
   * the collection of filters
   *
   * @var array
   */
  private $filters = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    $this->filters = array();
  }

  /**
   * This method add a filter.
   *
   * @param string $decription a short description of the file type
   * @param string $pattern the pattern of the filetype, allowing wildcards
   *
   * @return FileFilters $this
   */
  public function add($decription, $pattern) {
    $this->filters[] = array($decription, $pattern);

    return $this;
  }

  /**
   * This method returns the file filters as array.
   *
   * @return array[int]string
   */
  public function toArray() {
    $result = array();
    foreach($this->filters as $filter) {
      $result[] = $filter;
    }

    return $result;
  }
}