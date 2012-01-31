<?php

namespace Woody\Event;


interface ActionListener {
    function actionPerformed($actionEvent);
}