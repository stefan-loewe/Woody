<?php

use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;
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
//$box1 = new EditBox("abc", new Point(20, 20), new Dimension(720, 18));
//$win->add($box1);



$htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=3', new Point(50, 50), new Dimension(600, 400));
$win->add($htmlControl);
$htmlControl->setUrl("www.wieistmeineip.de");

$win->startEventHandler();
/*
$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));

$win->create(null);

$frame = new \Woody\Components\Controls\Frame('my new frame', new Point(20, 20), new Dimension(760, 200));
$win->add($frame);

$box1 = new EditBox("1111", new Point(20, 20), new Dimension(720, 18));
$box2 = new EditBox("2222", new Point(20, 240), new Dimension(760, 18));

$frame->add($box1);
$win->add($box2);

$win->add($t = new Woody\Components\Controls\Tab("noneReally", new Point(20, 280), new Dimension(760, 200)));
$t->addPage();

$frame2 = new \Woody\Components\Controls\Frame('my new frame', new Point(40, 320), new Dimension(720, 160));
$win->add($frame2);

$frame2->add(new EditBox("3333", new Point(40, 20), new Dimension(720, 18)));

$timerTest = new Timer(function() use ($box1, $timerTest)
                   {
                            $box1->setValue('abc');

                            $timerTest->destroy();
                        }, 500);
$timerTest->start($win);

$timerTest2 = new Timer(null, 750);

$stdClass = new stdClass();
$stdClass->cnt = 0;
$timerTest2->setCallback($f = function() use ($box1, $timerTest2, $stdClass)
                   {var_dump($stdClass->cnt);
                            $box1->setValue('def'.($stdClass->cnt++));

                            //$timerTest2->destroy();
                        });
$timerTest2->start($win);

$win->startEventHandler();
*/