<?php

/*******************************************************************************

 WINBINDER - The native Windows binding for PHP for PHP

 Copyright © Hypervisual - see LICENSE.TXT for details
 Author: Rubem Pechansky (http://winbinder.org/contact.php)

 Main inclusion file for WinBinder

*******************************************************************************/

if(!extension_loaded('winbinder') && !dl('php_winbinder.dll')) {
	trigger_error("WinBinder extension could not be loaded.\n", E_USER_ERROR);
}

// WinBinder PHP functions
require_once 'wb_windows.inc.php';
require_once 'wb_generic.inc.php';
require_once 'wb_resources.inc.php';