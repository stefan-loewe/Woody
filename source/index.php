<?php

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Components\Windows\MainWindow;
use \Woody\App\TestApplication;

use \Woody\Components\Timer\Timer;

use \Woody\Components\Controls\Frame;
use \Woody\Components\Controls\EditBox;
use \Woody\Components\Controls\Checkbox;

use \Woody\Util\ImageResource;

use \Utils\Http\HttpRequest;
use \Utils\Sockets\ServerSocket;

require_once(realpath(__DIR__.'/bootstrap/bootstrap.php'));

$fh = popen('"C:\Program Files\PHP54\php.exe" -S 127.0.0.1:8009 "D:\workspace\programming\PHP\woody\source\server.php" > server_log.txt', 'r');
//stream_set_blocking($fh, 0);
//$fh = popen('start "srv" D:\\workspace\\programming\\PHP\\woody\\source\\run_server.bat', 'r');
//while(!feof($fh))
//{
  //echo fread($fh, 128);
//}

//$desc = array(1 => array('file', 'server_log.txt', 'a'));
//$desc = array(
//   1 => array("pipe", "w")  // STDOUT ist eine Pipe, in die das Child schreibt
//);
//$pipes = array();

//$proc = proc_open('D:\\workspace\\programming\\PHP\\woody\\source\\run_server.bat', $desc, $pipes);
//$proc = proc_open('"C:\\Program Files\\PHP54\\php.exe" -S 127.0.0.1:8009 D:\\workspace\\programming\\PHP\\woody\\source\\server.php', $desc, $pipes);

//$options = array('bypass_shell' => true);
//proc_open('start "srv" D:\\workspace\\programming\\PHP\\woody\\source\\run_server.bat', $desc, $pipes);

//$id = 0xff3;
//$data = shmop_read($id, 0, 1024);
//var_dump($data);

class Handler implements Woody\Event\ActionListener {
    public function actionPerformed($actionEvent) {
        echo PHP_EOL.'hey, you triggered an action on the field with the value '.$this->value;
    }
}

class FocusHandler implements Woody\Event\FocusListener {
    public function focusGained(\Woody\Event\FocusEvent $event) {
        //echo PHP_EOL.'hey, you focus the field with the value '.$event->getFocusGainedComponent()->getValue();
        $event->getFocusGainedComponent()->setValue('focused');
    }
}

class KeyHandler implements Woody\Event\KeyListener {
    public function keyPressed(\Woody\Event\KeyEvent $event) {
        var_dump($event);
        echo PHP_EOL.'hey, you pressed key '.$event->getPressedKey().' on field with value '.Woody\Components\Component::getControlByID($event->controlID)->getValue();
    }

    public function keyReleased(\Woody\Event\KeyEvent $event) {
        echo PHP_EOL.'hey, you released key '.$event->getPressedKey().' on field with value '.Woody\Components\Component::getControlByID($event->controlID)->getValue();
    }
}

class KeyAdapter implements Woody\Event\KeyListener {
    private $onKeyPressed = null;
    private $onKeyReleased = null;

    public function __construct(callable $onKeyPressed = null, callable $onKeyReleased = null) {
        $this->onKeyPressed     = $onKeyPressed;
        $this->onKeyReleased    = $onKeyReleased;
    }

    public function keyPressed(\Woody\Event\KeyEvent $event) {
        if($this->onKeyPressed != null)
            $this->onKeyPressed->__invoke($event);
    }

    public function keyReleased(\Woody\Event\KeyEvent $event) {
        if($this->onKeyReleased != null)
            $this->onKeyReleased->__invoke($event);
    }
}
if(!TRUE)
{
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $server = new \Woody\Server\HtmlControlServer($win, 8008);

    $server->run(100);

    $check = new Checkbox(TRUE, new Point(10, 10), new Dimension(20, 20));
    $win->add($check);

    $htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=3', new Point(50, 50), new Dimension(600, 100));
    $win->add($htmlControl);
    $htmlControl2 = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=4', new Point(50, 200), new Dimension(600, 100));
    $win->add($htmlControl2);

    Woody\Event\EventHandler::addEventHandler($htmlControl2,
        function($control, ServerSocket $clientSocket, HttpRequest $httpRequest)
        {
            $keyValuePairs = $httpRequest->getKeyValuePairs();

            if($keyValuePairs['id'] == 3)
                $clientSocket->write('void');

            else
                $clientSocket->write('<a href="?id='.($keyValuePairs['id'] - 1)
                        .'">-</a><b style="color:red;">goodbye '
                        .$keyValuePairs['id'].'</b><a href="?id='
                        .($keyValuePairs['id'] + 1).'">+</a>');
        });

    $server->register($htmlControl2);
}

else if(!TRUE)
{
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $listControl = new Woody\Components\Controls\ListBox(new Point(10, 10), new Dimension(100, 300));
    $win->add($listControl);

    $comboControl = new Woody\Components\Controls\ComboBox(new Point(120, 10), new Dimension(100, 300));
    $win->add($comboControl);

    $data = new ArrayObject();
    $data[] = 1;
    $data[] = 'eins';
    $data[] = 2;
    $data[] = 'zwei';
    $data[] = 1.2;
    $data[] = new Woody\Event\Event(1, 2, 3, 4, 5);

    $comboControl->setModel($model = new \Woody\Model\ListModel($data));
    $listControl->setModel($model);
    $model->attach($comboControl);
    $model->attach($listControl);
    $model->notify();

    $button = new \Woody\Components\Controls\PushButton("click me", new Point(440, 140), new Dimension(100, 20));
    $win->add($button);
} else if(!TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $table = new Woody\Components\Controls\Table(new Point(10, 10), new Dimension(100, 300));

    $model = new \Woody\Model\DefaultTableModel(array(array(100, 200, 300, 400), array(10, 20, 30, 40)));
    $win->add($table);
    $table->setModel($model);
} else if(!TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $gauge = wb_create_control($win->getControlID(), Gauge, "Update", 230, 245, 310, 15, 287525);
    var_dump(wb_set_value($gauge, 50));
    var_dump(wb_get_value($gauge, 50));
} else if(!TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $treeView = new \Woody\Components\Controls\TreeView(new Point(10, 10), new Dimension(300, 400));
    $win->add($treeView);
    $root = new Utils\Tree\TreeNode('test1');
    $root->populateRandomly(100, 50);
    $treeView->setModel(new \Woody\Model\DefaultTreeModel($root));
} else if(!TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $tab = new \Woody\Components\Controls\Tab(new Point(10, 10), new Dimension(300, 400));
    $win->add($tab);
    $tab->addPage('title1');
    $tab->addPage('title2');
} else if(!TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
    $win->create();

    $imageFileName = 'D:/workspace/programming/PHP/woody/paypal.jpg';
    $dimension  = getimagesize($imageFileName);
    $dimension  = new Dimension($dimension[0] * 1, $dimension[1] * 1);
    $resource   = new ImageResource($imageFileName);

    $img = new \Woody\Components\Controls\Image($resource, new Point(10, 10), new Dimension(300, 400));
    $win->add($img);

    $d = new \Woody\Dialog\PopUp\YesNoCancelConfirmationDialog('OK?', 'really ok');
    $d->open();
    var_dump($d->yes());
    var_dump($d->no());
    var_dump($d->cancel());
    $d = new \Woody\Dialog\PopUp\YesNoConfirmationDialog('OK?', 'really ok');
    $d->open();
    var_dump(" ");
    var_dump($d->yes());
    var_dump($d->no());
    $d = new \Woody\Dialog\PopUp\OkCancelConfirmationDialog('OK?', 'really ok');
    $d->open();
    var_dump(" ");
    var_dump($d->ok());
    var_dump($d->cancel());
} else if(TRUE) {
    $win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(400, 300));
    $win->create();

    $box1 = new \Woody\Components\Controls\EditBox('', new Point(10, 10), new Dimension(300, 22));
    $win->add($box1);
    $box2 = new \Woody\Components\Controls\EditBox('', new Point(10, 35), new Dimension(300, 22));
    $win->add($box2);
    //$box1->addActionListener(new Handler());
    $box1->addFocusListener(new FocusHandler());
    //$box1->addKeyListener(new KeyHandler());
    $box1->addKeyListener(
            new KeyAdapter(
                    null, function($ev) {
                        $currentValue = $ev->getSource()->getValue();
                        if(strlen($currentValue) > 3) {
                            $ev->getSource()
                                    ->setValue(substr($currentValue, 0, 3))
                                    ->setCursor(3);
                        }
                    }));
}

$timer = new Timer(function() {
    //echo 'time: '.microtime(true);
    //echo fread($fh, 128);
    //echo "\n";

    $log = fopen('server_log.txt', 'r');
    //echo 'time: '.microtime(true);
    stream_set_blocking($log, 0);
    $result = fread($log, 128);
    fclose($log);
    if(strlen(trim($result)) > 0)
        echo $result."\n";

    file_put_contents('result.html', microtime(true).' -> '.$result);


}, $win, 1000);

$timer->start();
var_dump('start app');
$win->startEventHandler();
var_dump('stopp app');

pclose($fh);
var_dump('stopp server');