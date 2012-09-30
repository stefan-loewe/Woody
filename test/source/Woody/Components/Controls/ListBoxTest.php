<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

require_once 'ListControlTest.php';

/**
 * Test class for ListBox.
 * Generated by PHPUnit on 2011-12-05 at 22:44:52.
 */
class ListBoxTest extends ListControlTest {

  /**
   * This method returns the object under test to be used in the parent test case class.
   *
   * @return ComboBox the object under test
   */
  protected function getObjectUnderTest() {
    return new ListBox(new Point(20, 20), new Dimension(80, 200));
  }
}