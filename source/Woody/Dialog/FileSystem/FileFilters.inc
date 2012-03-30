<?php

namespace Woody\Dialog\FileSystem;

use \Woody\Components\Windows\AbstractWindow;

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

    public function add($decription, $pattern) {
        $this->filters[] = array($decription, $pattern);

        return $this;
    }

    public function toArray() {
        return $this->filters;
    }
}