<?php

namespace Woody\Components\Windows;

use Woody\Components\Windows\AbstractWindow;
use Woody\Utils\Misc\Rectangle;

class MainWindow extends AbstractWindow
{
	public function __construct($id, $label, Rectangle $rectangle, Window $parentWindow = null, $style = 0, $param = 0)
	{
		parent::__construct(AppWindow, $id, $label, $rectangle, $style, $param, $parentWindow);
	}
}