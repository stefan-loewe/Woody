<?php


require_once '../bootstrap/phpunit.php';


use Woody\Components\Windows\MainWindow;
use Woody\Utils\Misc\Rectangle;

$win1 = new Woody\Components\Windows\MainWindow('myWin1', 'MyWin1', $r = new Rectangle(50, 50, 300, 200));
$win1->create();
$win1->initialize();




$win2 = new MainWindow('myWin2', 'MyWin2', $r->moveBy(100, 100));
$win2->create();
$win2->initialize();
$win2->moveBy(300, 300);
sleep(1);
$win2->moveBy(-300, -300);
sleep(1);
$win2->moveBy(300, 300);
sleep(1);


$win2->moveTo(0, 0);
sleep(1);
$win2->moveTo(900, 0);
sleep(1);
$win2->moveTo(900, 900);
sleep(1);
$win2->moveTo(0, 900);
sleep(1);

$win2->moveTo(500, 500);
sleep(1);


$win2->resizeBy(10, 10);
sleep(1);
$win2->resizeBy(100, 100);
sleep(1);
$win2->resizeBy(-100, 300);
sleep(1);
$win2->resizeTo(50, 50);
sleep(1);