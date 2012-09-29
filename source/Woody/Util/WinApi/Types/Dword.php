<?php

namespace Woody\Util\WinApi\Types;

class Dword implements Type {
  public function getPackFormatCharacter() {
    return 'L';
  }

  public function getLength() {
    return 4;
  }
}