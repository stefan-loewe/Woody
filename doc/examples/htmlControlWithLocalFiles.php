<?php

use \Woody\App\Application;
use \Woody\Components\Windows\MainWindow;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Event\ActionEvent;
use \Woody\Components\Controls\HTMLControl;
use \Woody\Event\ActionAdapter;
use \Woody\Components\Controls\PushButton;

require_once(realpath(__DIR__.'../../../source/bootstrap/bootstrap.php'));

class HTMLControlDemo extends Application {

    /**
     * the BuiltInWebServer this application uses to server and interact with content
     *
     * @var BuiltInWebServer
     */
    private $server         = null;

    /**
     * the port on which the internal server listens
     */
    private $port           = null;

    /**
     * a file name, used for demonstration purpose, only
     *
     * @var string
     */
    private $selectedFile   = null;

    public function __construct($port) {
        parent::__construct();

        $this->port         = $port;

        $this->window       = new MainWindow('built-in-webserver', new Point(50, 50), new Dimension(800, 500));
        $this->window->create();

        $this->htmlControl  = new HTMLControl('http://127.0.0.1:'.$this->port, new Point(50, 50), new Dimension(350, 300));
        $this->htmlControl->addActionListener(new ActionAdapter($this->getHtmlControlCallback()));
        $this->window->add($this->htmlControl);

        $this->editArea     = new Woody\Components\Controls\EditArea('', new Point(450, 50), new Dimension(300, 300));
        $this->window->add($this->editArea);

        $this->btnRefresh   = new PushButton("refresh", new Point(50, 370), new Dimension(150, 22));
        $this->btnRefresh->addActionListener(new ActionAdapter($this->getBtnRefreshCallback()));
        $this->window->add($this->btnRefresh);

        $this->btnBrowse    = new PushButton("browse ...", new Point(220, 370), new Dimension(150, 22));
        $this->btnBrowse->addActionListener(new ActionAdapter($this->getBtnBrowseCallback()));
        $this->window->add($this->btnBrowse);
    }

    private function getHtmlControlCallback() {
        return function(ActionEvent $event) {
                    $uri = $event->property->getUri();

                    // get static content
                    if(preg_match('/\.(?:png|jpg|jpeg|gif)$/', $uri)) {
                        $event->type->write(file_get_contents('D:\\workspace\\programming\\PHP\\woody\\doc\\examples\\www'.$uri));
                    }

                    else {
                        $keyValuePairs = $event->property->getKeyValuePairs();

                        if(isset($keyValuePairs['eventData'])) {
                            $this->editArea->setValue(urldecode($keyValuePairs['eventData']));
                        }
                        else {
                            $content = '<html>
                                            <head>
                                                <title>
                                                    Woody - interactive HTMLControl
                                                </title>
                                                <script type="text/javascript">
                                                    function sendEventData(event) {
                                                        data = "";
                                                        for(prop in event)
                                                            data = prop + ": " + event[prop] + "\n" + data;

                                                        data = escape(data);

                                                        conn = new XMLHttpRequest();
                                                        conn.open("GET", "http://127.0.0.1:'.$this->port.'?eventData=" + data, true);
                                                        conn.send(null);
                                                    }
                                                </script>
                                            </head>
                                            <body onclick="sendEventData(event)">
                                                <h1>Woody</h1>
                                                <div>
                                                    <img src="woody.png" alt="woody logo"/>
                                                </div>
                                                <div>
                                                    this site was requested on '.date('d.m.Y \a\t H:i:s', isset($keyValuePairs['time']) ? $keyValuePairs['time'] : time()).'
                                                    <br>
                                                    this site was generated on '.date('d.m.Y \a\t H:i:s').'
                                                    <br>
                                                    name of selected file: '.($this->selectedFile == null ? 'none selected' : '"'.$this->selectedFile.'"').'
                                                    <br>contents: '.($this->selectedFile == null ? 'none selected' : '<br><div style="position:relative;border:solid 1px black; overflow:scroll; width:100%; height:200px;">'.file_get_contents($this->selectedFile)).'</div>
                                                </div>
                                            </body>
                                        </html>';
                            $event->type->write($content);
                        }
                    }
                };
    }

    private function getBtnRefreshCallback() {
        return function() {
            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port.'?time='.time());
        };
    }

    private function getBtnBrowseCallback() {
        return function() {
            $this->selectedFile = trim(wb_sys_dlg_open($this->window->getControlID(), 'please select a file', null, __DIR__, null));

            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port);
        };
    }

    public function start() {
        $this->server = new \Woody\Server\HtmlControlServer($this->window,
                                                            $this->port,
                                                            __DIR__.'\\www');
        $this->server->start(100)
                     ->register($this->htmlControl);

        $this->window->startEventHandler();
    }

    public function stop() {
        $this->server->stop();
    }
}

$app = new HTMLControlDemo(5556);
$app->start();
$app->stop();