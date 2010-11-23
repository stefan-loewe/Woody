<?php

require_once '../lib/winbinder.php';

require_once '../source/Components/Windows/AbstractWindow.class.inc';
require_once '../source/Components/Windows/MainWindow.class.inc';
require_once '../source/Utils/Misc/Rectangle.class.inc';

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