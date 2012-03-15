<?php

namespace Woody\App;

abstract class Application {
    /**
     * the main window of the application
     *
     * @var \Woody\Components\Windows\AbstractWindow
     */
    protected $window = null;

    public function __construct() {
    }

    abstract public function start();

    abstract public function stop();
}