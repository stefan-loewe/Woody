<?php

use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;
use Woody\Components\Windows\MainWindow;

use Woody\Components\Controls\Frame;

require_once(realpath(__DIR__.'/bootstrap/bootstrap.php'));

$win = new MainWindow('myWin2', 'MyWin2', new Point(50, 50), new Dimension(300, 200));

$win->create();

$f = new Frame();
$win->add($f);

$win->startEventHandler();