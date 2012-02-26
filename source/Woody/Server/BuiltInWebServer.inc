<?php

namespace Woody\Server;

use \Woody\Components\Windows\AbstractWindow;
use \Woody\Components\Timer\Timer;
use \Woody\Components\Controls\HtmlControl;

class BuiltInWebServer {

    private $port       = 0;

    private $process    = null;

    private $window     = null;

    private $timer      = null;

    private $htmlControls   = null;

    private static $log = 'server_log.txt';

    public function __construct(AbstractWindow $window, $port) {
        $this->window = $window;
        $this->port = $port;

        $this->htmlControls = new \SplObjectStorage();
    }

    public function run() {
        $this->startWebServerProcess();

        $this->startTimer();
    }

    public function shutdown() {
        proc_terminate($this->process);
    }

    private function startWebServerProcess() {
        $descriptors = array(1 => array('file', self::$log, 'w'));
        $pipes       = array();

        // bypass_shell is true, so that process can be terminated, and is not "embedded" in cmd.exe process
        $this->process = proc_open('"C:\\Program Files\\PHP54\\php.exe" -S 127.0.0.1:'.$this->port.' D:\\workspace\\programming\\PHP\\woody\\source\\server.php',
                            $descriptors,
                            $pipes,
                            null,
                            null,
                            array('bypass_shell' => true));
    }

    private function startTimer() {
        $this->timer = new Timer(function() {
            $this->processClient();
        }, $this->window, 1000);

        $this->timer->start();
    }

    private function processClient() {
        $uri = $this->readLog();

        if(strlen(trim($uri)) > 0) {
            $uri = trim(str_replace('uri: /?site=', '', $uri));

            exec('"C:\\Program Files\\PHP54\\php.exe" D:\\workspace\\programming\\PHP\\ReingoldTilford\\trunk\\bin\\example_directory_tree_dynamic.php > rt_result.html');

            $src = 'D:\workspace\programming\PHP\woody\source\rt_result.html';
            //if(mt_rand() % 2 === 1)
                $src = 'D:\workspace\programming\PHP\woody\source\self.html';

            file_put_contents(WEB_SERVER_BUFFER, file_get_contents($src));

            foreach($this->htmlControls as $htmlControl) {
                \Woody\Event\EventFactory::createEvent(
                        //not public $htmlControl->getWindow()->getControlID(),
                        null,
                        $htmlControl->getID(),
                        $htmlControl->getControlID(),
                        $clientSocket,
                        $request = HttpRequestFactory::createRequest($message));
            }
        }
    }

    private function readLog() {
        $fileHandle = fopen(self::$log, 'r');
        stream_set_blocking($fileHandle, 0);
        $result = fread($fileHandle, 128);
        fclose($fileHandle);

        $fileHandle = fopen(self::$log, 'w');
        fclose($fileHandle);

        return $result;
    }

    public function register(HtmlControl $control) {
        $this->htmlControls[$control] = TRUE;

        return $this;
    }

    public function unregister(HtmlControl $control) {
        $this->htmlControls->detach($control);

        return $this;
    }
}