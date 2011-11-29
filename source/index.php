<?php

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Components\Windows\MainWindow;
use \Woody\App\TestApplication;

use \Woody\Components\Timer\Timer;

use \Woody\Components\Controls\Frame;
use \Woody\Components\Controls\EditBox;

require_once(realpath(__DIR__.'/bootstrap/bootstrap.php'));

/*$fh = popen('start run_server.bat', 'r');
while(!feof($fh))
{
    echo fread($fh, 128);
}*/
/*
$desc = array(0 => array('file', 'D:/r.txt', 'a'), 1 => array('file', 'D:/w.txt', 'a'), 2 => array('file', 'D:/e.txt', 'a'));
$pipes = array();

chdir("C:/Program Files/PHP54/");
proc_open('"C:/Program Files/PHP54/php.exe" -S 127.0.0.1:8008', $desc, $pipes);

die;*/
var_dump(phpversion());

$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
$win->create();

$server = new \Woody\Server\HtmlControlServer($win, 8008);

$server->run(100);

$htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=3', new Point(50, 50), new Dimension(600, 100));
$win->add($htmlControl);
$htmlControl2 = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=4', new Point(50, 200), new Dimension(600, 100));
$win->add($htmlControl2);
var_dump('$htmlControl1-> '.$htmlControl->getID());
var_dump('$htmlControl2-> '.$htmlControl2->getID());
Woody\Event\EventHandler::addEventHandler($htmlControl2, function($control, $param1, $param2){if($param2->getKeyValuePairs()['id'] == 3)return;$param1->write('<a href="?id='.($param2->getKeyValuePairs()['id'] - 1).'">-</a><b style="color:red;">goodbye '.$param2->getKeyValuePairs()['id'].'</b><a href="?id='.($param2->getKeyValuePairs()['id'] + 1).'">+</a>');});

$server->register($htmlControl2);

$win->startEventHandler();
