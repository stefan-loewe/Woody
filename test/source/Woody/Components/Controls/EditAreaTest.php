<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\App\TestApplication;
use \ws\loewe\Woody\Components\Timer\Timer;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

/**
 * Test class for EditArea.
 * Generated by PHPUnit on 2011-11-15 at 23:23:15.
 */
class EditAreaTest extends \PHPUnit_Framework_TestCase {

  /**
   * the edit area to test
   *
   * @var \ws\loewe\Woody\Components\Controls\EditArea
   */
  private $editArea = null;

  /**
   * the test application
   *
   * @var \ws\loewe\Woody\App\TestApplication
   */
  private $application = null;

  /**
   * the timer for the test application
   *
   * @var \ws\loewe\Woody\Components\Timer\Timer
   */
  private $timer = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->editArea = new EditArea('testValue', new Point(20, 20), new Dimension(100, 80));

    $this->application->getWindow()->getRootPane()->add($this->editArea);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method tests retrieving the value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetValue() {
    $this->timer = new Timer(function() {
                              $this->assertEquals('testValue', $this->editArea->getValue());
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the trimmed value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetValueTrimmed() {
    $this->timer = new Timer(function() {
                              $this->editArea->setValue('     testValue     ');
                              $this->assertEquals('testValue', $this->editArea->getValue());
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the non-trimmed value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetValueNotTrimmed() {
    $this->timer = new Timer(function() {
                              $value = '     testValue     ';
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests retrieving the multi-line value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetMutiLine() {
    $this->timer = new Timer(function() {
                              $value = "some\r\nNew\r\n\r\nValue";
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue());
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the multi-line trimmed value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetMutiLineTrimmed() {
    $this->timer = new Timer(function() {
                              $value = "\r\n   \r\n  \r\n   some\r\nNew\r\n\r\nValue    \r\n   \r\n  \r\n   ";
                              $this->editArea->setValue($value);
                              $this->assertEquals(trim($value), $this->editArea->getValue());
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the multi-line untrimmed value from the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::getValue
   */
  public function testGetMutiLineNotTrimmed() {
    $this->timer = new Timer(function() {
                              $value = "\r\n   \r\n  \r\n   some\r\nNew\r\n\r\nValue    \r\n   \r\n  \r\n   ";
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests setting a text value for the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::setValue
   */
  public function testSetValueText() {
    $this->timer = new Timer(function() {
                              $value = 'someNewValue';
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests setting a integer value for the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::setValue
   */
  public function testSetValueInteger() {
    $this->timer = new Timer(function() {
                              $value = 123;
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests setting a decimal value for the edit area.
   *
   * @covers \ws\loewe\Woody\Components\Controls\EditArea::setValue
   */
  public function testSetValueDecimal() {
    $this->timer = new Timer(function() {
                              $value = 123.99;
                              $this->editArea->setValue($value);
                              $this->assertEquals($value, $this->editArea->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }
}