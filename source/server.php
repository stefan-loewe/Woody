<?php

require_once('bootstrap/bootstrap.php');

deleteBuffer();

printCurrentUrl();

printBuffer();

function printBuffer() {
    while(!file_exists(WEB_SERVER_BUFFER)) {
        sleep(1);
    }

    echo file_get_contents(WEB_SERVER_BUFFER);
}


function deleteBuffer() {
    if(file_exists(WEB_SERVER_BUFFER)) {
        unlink(WEB_SERVER_BUFFER);
    }
}

function printCurrentUrl() {
    $fh = fopen('php://stdout', 'w');
    fputs($fh, $_SERVER['REQUEST_URI']);
    fclose($fh);
}