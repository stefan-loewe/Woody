<?php

namespace Woody\Server;

use \Woody\Components\Windows\AbstractWindow;
use \Woody\Components\Timer\Timer;
use \Woody\Components\Controls\HtmlControl;

class BuiltInWebServer {
    private $window         = null;

    private $port           = 0;

    private $htmlServer     = null;

    private $process        = null;

    private $documentRoot   = null;

    public function __construct(AbstractWindow $window, $port, $documentRoot) {
        $this->window       = $window;
        $this->port         = $port;
        $this->documentRoot = $documentRoot;
    }

    public function start() {
        $this->startWebServerProcess();

        $this->startHtmlReplyServer();

        return $this;
    }

    public function stop() {
        proc_terminate($this->process);

        return $this;
    }

    private function startWebServerProcess() {
        $descriptors = array();
        $pipes       = array();

        // bypass_shell is true, so that process can be terminated, and is not "embedded" in cmd.exe process
        $this->process = proc_open('"C:\\Program Files\\PHP54\\php.exe" -S 127.0.0.1:'.$this->port.' -t "'.$this->documentRoot.'" D:\\workspace\\programming\\PHP\\woody\\source\\server.php',
                            $descriptors,
                            $pipes,
                            null,
                            null,
                            array('bypass_shell' => true));
    }

    public function register(HtmlControl $control)
    {
        $this->htmlServer->register($control);

        return $this;
    }

    /**
     * This method unregisters the given HtmlControl.
     *
     * @param HtmlControl $control the HtmlControl to be removed
     * @return HtmlControlServer $this
     */
    public function unregister(HtmlControl $control)
    {
        $this->htmlServer->unregister($control);

        return $this;
    }

    private function startHtmlReplyServer() {
        $this->htmlServer = new HtmlControlServer($this->window, 1234);

        $this->htmlServer->start(100);
    }
}