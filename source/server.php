<?php

echo 'uri: '.$_SERVER['REQUEST_URI'];

if(file_exists('result.html'))
    unlink('result.html');
//$id = 0xff3;
//shmop_open($id, "c", 0666, 1024);
//shmop_write($id, $_SERVER['REQUEST_URI'], 0);

$fp = fopen('php://stdout', 'w');
fputs($fp, 'uri: '.$_SERVER['REQUEST_URI']);

//while(!file_exists('result.html'))
  //  sleep(1);

echo file_get_contents('result.html');