<?php

namespace Woody\Util\WinApi\Types;

class Dword {
  public function getPackFormatCharacter() {
    return 'L';
  }

  public function getLength() {
    return 4;
  }
}