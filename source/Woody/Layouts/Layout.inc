<?php

namespace Woody\Layouts;

use \Woody\Components\Container;

interface Layout {

  /**
   * This method layouts the given container using this layout.
   *
   * @param \Woody\Components\Container $container
   */
  function layout(Container $container);
}