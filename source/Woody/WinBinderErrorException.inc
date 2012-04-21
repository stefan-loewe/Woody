<?php

namespace Woody;

/**
 * This class represents an Exception that is thrown if a winbinder function raises an error while being executed.
 * @todo: is this practical? -> errors can be converted to exceptions, but is this possible/feasible for some files or functions (wb_*) only
 */
class WinBinderErrorException extends \RuntimeException {
  public function __construct($message, $code = 0, \Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }
}