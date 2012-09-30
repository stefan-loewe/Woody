<?php

namespace ws\loewe\Woody;

/**
 * This class represents an Exception that is thrown if a winbinder function raises an error while being executed.
 */
class WinBinderErrorException extends \RuntimeException {
  public function __construct($message, $code = 0, \Exception $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }
}