<?php

namespace ws\loewe\Woody\Event;

interface ActionListener {
  function actionPerformed(ActionEvent $actionEvent);
}