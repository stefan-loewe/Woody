<?php

use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;
use Woody\Components\Windows\MainWindow;

use Woody\Components\Controls\Frame;
use Woody\Components\Controls\EditBox;

require_once(realpath(__DIR__.'/bootstrap/bootstrap.php'));

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

$win->startEventHandler();