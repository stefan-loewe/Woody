<?php

namespace ws\loewe\Woody\Util\WinApi\Types;

interface Type {
  /**
   * This method returns the pack-format charachter of this type.
   */
  function getPackFormatCharacter();

  /**
   * This method returns the byte lenfth of this type.
   */
  function getLength();
}