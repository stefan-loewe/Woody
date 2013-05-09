<?php

namespace ws\loewe\Woody\Layouts;

use ws\loewe\Woody\Components\Controls\Frame;

interface Layout {

  /**
   * This method layouts the given container using this layout.
   *
   * @param Frame $container
   */
  function layout(Frame $container);
}