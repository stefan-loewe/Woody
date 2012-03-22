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

        $this->htmlControl  = new HTMLControl('http://127.0.0.1:'.$this->port, new Point(20, 25), new Dimension(760, 300));
        $this->htmlControl->addActionListener(new ActionAdapter($this->getHtmlControlCallback()));
        $this->window->add($this->htmlControl);

        $this->btnRoot      = new PushButton("document root", new Point(20, 345), new Dimension(100, 22));
        $this->btnRoot->addActionListener(new ActionAdapter($this->getBtnRootCallback()));
        $this->window->add($this->btnRoot);

        $this->btnWeb       = new PushButton("www.google.com", new Point(135, 345), new Dimension(100, 22));
        $this->btnWeb->addActionListener(new ActionAdapter($this->getBtnWebCallback()));
        $this->window->add($this->btnWeb);

        $this->btnPhpInfo   = new PushButton("phpinfo()", new Point(250, 345), new Dimension(100, 22));
        $this->btnPhpInfo->addActionListener(new ActionAdapter($this->getBtnPhpInfoCallback()));
        $this->window->add($this->btnPhpInfo);
    }

    private function getHtmlControlCallback() {
        return function(ActionEvent $event) {
                    $keyValuePairs = $event->property->getKeyValuePairs();

                    $content = '<h1>header, large</h1>
                                <br>time = '.$keyValuePairs['time'].'
                                <br>followed by an image ...
                                <br><img src="woody.png">';
                    $event->type->write($content);
                };
    }

    private function getBtnRootCallback() {
        return function() {
            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port);
        };
    }

    private function getBtnWebCallback() {
        return function() {
            $this->htmlControl->setUrl('http://www.google.de');
        };
    }

    private function getBtnPhpInfoCallback() {
        return function() {
            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port.'/phpinfo.php');
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