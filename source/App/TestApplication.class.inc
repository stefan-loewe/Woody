<?php

namespace Woody\App;

use Woody\Components\Windows\MainWindow;
use Woody\Components\Timer\Timer;
use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;

class TestApplication extends Application
{
    private $hasFinished        = false;

    private $shutdownTimer      = null;

    private $shutdownCallback   = null;

    public function __construct()
    {
        $this->window   = new MainWindow('TestApp', new Point(50, 50), new Dimension(300, 200));

        $this->shutdownCallback =   function()
                                    {
                                        if($this->hasFinished)
                                        {
                                            $this->shutdownTimer->destroy();

                                            $this->window->destroy();
                                        }
                                    };

        $this->shutdownTimer    = new Timer($this->shutdownCallback, 1000);

        $this->window->create(null);

        $this->shutdownTimer->start($this->window);
    }

    public function getWindow()
    {
        return $this->window;
    }

    public function start()
    {
        $this->window->startEventHandler();
    }

    public function stop()
    {
        $this->hasFinished = TRUE;
    }
}