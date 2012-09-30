<?php

namespace ws\loewe\Woody\Layouts;

use \ws\loewe\Woody\Components\Container;

interface Layout {

  /**
   * This method layouts the given container using this layout.
   *
   * @param \ws\loewe\Woody\Components\Container $container
   */
  function layout(Container $container);
}