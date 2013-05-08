<?php

use \ws\loewe\Utils\Geom\Dimension;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Logging\Logger;
use Woody\App\Application;
use Woody\Components\Controls\EditArea;
use Woody\Components\Controls\HTMLControl;
use Woody\Components\Controls\PushButton;
use Woody\Components\Windows\MainWindow;
use Woody\Dialog\FileSystem\FileFilters;
use Woody\Dialog\FileSystem\FileOpenDialog;
use Woody\Event\ActionAdapter;
use Woody\Event\ActionEvent;
use Woody\Server\HtmlControlServer;

require_once(realpath(__DIR__.'../../../source/bootstrap/bootstrap.php'));

class HTMLControlDemo extends Application {

    /**
     * the HtmlControlServer this application uses to serve and interact with content
     *
     * @var HtmlControlServer
     */
    private $server         = null;

    /**
     * the port on which the internal server listens
     */
    private $port           = null;

    /**
     * the document root of the web server
     *
     * @var string
     */
    private $documentRoot = null;

    /**
     * a file name, used for demonstration purpose, only
     *
     * @var string
     */
    private $selectedFile   = null;

    public function __construct($port) {
        parent::__construct();

        Logger::setLogLevel(Logger::ALL);

        $this->port         = $port;
        $this->documentRoot = __DIR__.'\\www';

        $this->window       = new MainWindow('custom webserver', new Point(50, 50), new Dimension(800, 500));
        $this->window->create();

        $this->editArea     = new EditArea('', new Point(450, 50), new Dimension(300, 300));
        $this->htmlControl  = new HTMLControl('http://127.0.0.1:'.$this->port, new Point(50, 50), new Dimension(350, 300));
        $this->btnRefresh   = new PushButton("refresh", new Point(50, 370), new Dimension(150, 22));
        $this->btnBrowse    = new PushButton("browse ...", new Point(220, 370), new Dimension(150, 22));

        $this->window->getRootPane()->add($this->htmlControl);
        $this->window->getRootPane()->add($this->editArea);
        $this->window->getRootPane()->add($this->btnRefresh);
        $this->window->getRootPane()->add($this->btnBrowse);

        $this->htmlControl->addActionListener(new ActionAdapter($this->getHtmlControlCallback()));
        $this->btnRefresh->addActionListener(new ActionAdapter($this->getBtnRefreshCallback()));
        $this->btnBrowse->addActionListener(new ActionAdapter($this->getBtnBrowseCallback()));
    }

    private function getHtmlControlCallback() {
        return function(ActionEvent $event) {
                    $uri = $event->property->getUri();

                    // get static content
                    if(preg_match('/\.(?:png|jpg|jpeg|gif)$/', $uri)) {
                        $event->type->write(file_get_contents($this->documentRoot.$uri));
                    }

                    else {
                        $keyValuePairs = $event->property->getKeyValuePairs();

                        if(isset($keyValuePairs['eventData'])) {
                            $this->editArea->setValue(urldecode($keyValuePairs['eventData']));
                        }
                        else {
                            $content = '<html>'
                                            .'<head>'
                                                .'<title>'
                                                    .'Woody - interactive HTMLControl'
                                                .'</title>'
                                                .'<script type="text/javascript">'
                                                    .'function sendEventData(event) {'
                                                        .'data = "";'
                                                        .'for(prop in event)'
                                                            .'data = prop + ": " + event[prop] + "\n" + data;'

                                                        .'data = escape(data);'

                                                        .'conn = new XMLHttpRequest();'
                                                        .'conn.open("GET", "http://127.0.0.1:'.$this->port.'?eventData=" + data, true);'
                                                        .'conn.send(null);'
                                                    .'}'
                                                .'</script>'
                                            .'</head>'
                                            .'<body onclick="sendEventData(event)">'
                                                .'<h1>Woody</h1>'
                                                .'<div>'
                                                    .'this site was requested on '.date('d.m.Y \a\t H:i:s', isset($keyValuePairs['time']) ? $keyValuePairs['time'] : time())
                                                    .'<br>'
                                                    .'this site was generated on '.date('d.m.Y \a\t H:i:s')
                                                    .'<br>'
                                                    .'name of selected file: '.($this->selectedFile == null ? 'none selected' : '"'.$this->selectedFile.'"')
                                                    .'<br>contents: '.($this->selectedFile == null ? 'none selected' : '<br><div style="position:relative;border:solid 1px black; overflow:scroll; width:100%; height:200px;">'.nl2br(file_get_contents($this->selectedFile))).'</div>'
                                                .'</div>'
                                            .'</body>'
                                        .'</html>';

                            $header = "HTTP/1.1 200 OK"."\r\n"
                              ."Length: ".strlen($content)."\r\n"
                              ."Content-Type: text/html"."\r\n"."\r\n";

                            $event->type->write($header);
                            sleep(1);
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
            $fileFilters = new FileFilters();
            $fileFilters->add("Web page", "*.htm?")
                ->add("Text document", "*.txt")
                ->add("PHP source code", "*.php?")
                ->add("All files", "*.*");

            $fileOpenDialog = new FileOpenDialog(
              'please select a file to include',
              $this->window,
              __DIR__.'\\',
              $fileFilters);

            $fileOpenDialog->open();

            $this->selectedFile = $fileOpenDialog->getSelection();
            $this->htmlControl->setUrl('http://127.0.0.1:'.$this->port);
        };
    }

    public function start() {
        $this->server = new HtmlControlServer($this->window, $this->port, $this->documentRoot);
        $this->server->start(100)->register($this->htmlControl);

        $this->window->startEventHandler();
    }

    public function stop() {
        $this->server->stop();
    }
}

$app = new HTMLControlDemo(5556);
$app->start();
$app->stop();