<?php

namespace Woody\Server;

use \Woody\Components\Windows\AbstractWindow;
use \Woody\Components\Timer\Timer;
use \Woody\Components\Controls\HtmlControl;

class BuiltInWebServer {

    private $port       = 0;

    private $process    = null;

    private $window     = null;

    private $htmlServer = null;

    public function __construct(AbstractWindow $window, $port) {
        $this->window = $window;
        $this->port = $port;
    }

    public function run() {
        //$this->startWebServerProcess();

        $this->startHtmlReplyServer();
    }

    public function shutdown() {
        proc_terminate($this->process);
    }

    private function startWebServerProcess() {
        $descriptors = array();
        $pipes       = array();

        // bypass_shell is true, so that process can be terminated, and is not "embedded" in cmd.exe process
        $this->process = proc_open('"C:\\Program Files\\PHP54\\php.exe" -S 127.0.0.1:'.$this->port.' -t "C:\\Program Files\\Console" D:\\workspace\\programming\\PHP\\woody\\source\\server.php',
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

        $this->htmlServer->run(1000);
    }
}