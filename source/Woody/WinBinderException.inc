<?php

namespace Woody;

/**
 * This class represents an Exception that is thrown if a winbinder function signals an error by a return value.
 */
class WinBinderException extends \RuntimeException {
  public function __construct($message, $code = 0, \Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }
}