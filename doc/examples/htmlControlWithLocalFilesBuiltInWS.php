<?php

use \Woody\App\Application;
use \Woody\Components\Windows\MainWindow;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Event\ActionEvent;
use \Woody\Components\Controls\HTMLControl;
use \Woody\Event\ActionAdapter;
use \Woody\Components\Controls\PushButton;
use \Woody\Server\BuiltInWebServer;

require_once(realpath(__DIR__.'../../../source/bootstrap/bootstrap.php'));

class HTMLControlDemoBuiltInWebServer extends Application {

    /**
     * the BuiltInWebServer this application uses to server and interact with content
     *
     * @var BuiltInWebServer
     */
    private $server = null;

    private $port   = null;

    public function __construct($port) {
        parent::__construct();

        Utils\Logging\Logger::setLogLevel(Utils\Logging\Logger::OFF);

        $this->port         = $port;

        $this->window       = new MainWindow('built-in-webserver', new Point(50, 50), new Dimension(800, 500));
        $this->window->create();

        $this->htmlControl  = new HTMLControl('http://127.0.0.1:'.$this->port, new Point(50, 50), new Dimension(600, 300));
        $this->htmlControl->addActionListener(new ActionAdapter($this->getHtmlControlCallback()));
        $this->window->add($this->htmlControl);

        $this->btnRefresh   = new PushButton("refresh", new Point(170, 370), new Dimension(100, 22));
        $this->btnRefresh->addActionListener(new ActionAdapter($this->getBtnRefreshCallback()));
        $this->window->add($this->btnRefresh);
    }

    private function getHtmlControlCallback() {
        return function(ActionEvent $event) {

                    $keyValuePairs = $event->property->getKeyValuePairs();

                    $content = '<h1>header, large</h1>
                                <br>time = '.$keyValuePairs['time'].'
                                <br>followed by an image ...
                                <br><img src="A1.png">';
                    $event->type->write($content);
                };
    }

    private function getBtnRefreshCallback() {
        return function() {
            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port.'?time='.time());
        };
    }

    public function start() {
        $this->server = new BuiltInWebServer($this->window,
                                                            $this->port,
                                                            __DIR__.'\\www');
        $this->server->start()
                     ->register($this->htmlControl);

        $this->window->startEventHandler();
    }

    public function stop() {
        $this->server->stop();
    }
}

$app = new HTMLControlDemoBuiltInWebServer(5555);
$app->start();
$app->stop();