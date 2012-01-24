<?php

namespace Woody\Event;


interface ActionListener {
    public function actionPerformed($actionEvent);
}