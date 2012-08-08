<?php

namespace Woody\Util\WinApi;

abstract class Structure {

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {}

  /**
   * This method returns the packed binary representation of this object, so that it can be passed to the C backend.
   *
   * @return string packed binary representation of this object
   */
  public function pack() {
    $packString = '';

    foreach($this->getTypeMapping() as $type) {
      $typeClassName  = "\\Woody\\Util\\WinAPI\\Types\\".$type;
      $typeClass      = new $typeClassName();

      $packString = $packString.pack($typeClass->getPackFormatCharacter(), $typeClass->getLength());
    }

    return $packString;
  }

  /**
   * This method unpacks the given packed binary data, and maps it to this objects properties
   *
   * @param string $pack the binary data to unpack
   */
  public function unpack($pack) {
    $asArray = unpack($this->getUnpackFormatString(), $pack);

    foreach($asArray as $propertyName => $propertyValue) {
      $this->$propertyName = $propertyValue;
    }
  }

  /**
   * This method computes the format string for unpacking the binary representation of this class.
   *
   * @return string the format string needed for unpacking
   */
  private function getUnpackFormatString() {
    $unpackString = '';

    foreach($this->getTypeMapping() as $propertyName => $type) {

      $typeClassName  = "\\Woody\\Util\\WinApi\\Types\\".$type;
      $typeClass      = new $typeClassName();

      $unpackString = $unpackString.$typeClass->getPackFormatCharacter().$propertyName.'/';
    }

    return $unpackString;
  }

  /**
   * This method gets a mapping from class property name to the type of the class property.
   *
   * @return array[string]string
   */
  private function getTypeMapping() {
    $reflectionClass  = new \ReflectionClass($this);
    $properties       = $reflectionClass->getProperties();

    $mapping = array();

    foreach ($properties as $property) {
      $mapping[$property->getName()] = $this->extractType($property->getDocComment());
    }

    return $mapping;
  }

  /**
   * This method extracts the type name from a class properties' doc-block comment.
   *
   * @param string $docBlockComment the doc-block comment from where to extract the type name.
   * @return string the type name of the class property
   */
  private function extractType($docBlockComment) {
    $lines = explode("\n", $docBlockComment);

    foreach($lines as $line) {
      if(strpos(trim($line), '* @var') === 0) {
        return trim(str_replace('* @var', '', $line));
      }
    }

    return 'void';
  }
}