<?php

namespace Woody\Util\WinApi\Types;

class Byte {
  public function getPackFormatCharacter() {
    return 'C';
  }

  public function getLength() {
    return 1;
  }
}

