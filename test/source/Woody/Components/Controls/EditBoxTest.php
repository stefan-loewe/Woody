<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for EditBox.
 * Generated by PHPUnit on 2011-11-15 at 23:23:15.
 */
class EditBoxTest extends \PHPUnit_Framework_TestCase {

  /**
   * the edit box to test
   *
   * @var \Woody\Components\Controls\EditBox
   */
  private $editBox = null;

  /**
   * the test application
   *
   * @var \Woody\App\TestApplication
   */
  private $application = false;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->editBox = new EditBox('', new Point(20, 20), new Dimension(100, 18));

    $this->application->getWindow()->add($this->editBox);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {

  }

  /**
   * This method tests retrieving the value from the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::getValue
   */
  public function testGetValue() {
    $this->timer = new Timer(function() {
          $value = 'testGetValue';
          $this->editBox->setValue($value);
          $this->assertEquals($value, $this->editBox->getValue());

          $this->editBox->setValue('   ');
          $this->assertNull($this->editBox->getValue());

          $this->editBox->setValue(null);
          $this->assertNull($this->editBox->getValue());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the trimmed value from the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::getValue
   */
  public function testGetValueTrimmed() {
    $this->timer = new Timer(function() {
                              $value = '     testGetValueTrimmed     ';
                              $this->editBox->setValue($value);
                              $this->assertEquals(trim($value), $this->editBox->getValue());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the non-trimmed value from the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::getValue
   */
  public function testGetValueNotTrimmed() {
    $this->timer = new Timer(function() {
                              $value = '     testGetValueNotTrimmed     ';
                              $this->editBox->setValue($value);
                              $this->assertEquals($value, $this->editBox->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests setting a text value for the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::setValue
   */
  public function testSetValueText() {
    $this->timer = new Timer(function() {
                              $value = 'someNewValue';
                              $this->editBox->setValue($value);
                              $this->assertEquals($value, $this->editBox->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests setting a integer value for the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::setValue
   */
  public function testSetValueInteger() {
    $this->timer = new Timer(function() {
                              $value = 123;
                              $this->editBox->setValue($value);
                              $this->assertEquals($value, $this->editBox->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests setting a decimal value for the editbox.
   *
   * @covers \Woody\Components\Controls\EditBox::setValue
   */
  public function testSetValueDecimal() {
    $this->timer = new Timer(function() {
                              $value = 123.99;
                              $this->editBox->setValue($value);
                              $this->assertEquals($value, $this->editBox->getValue(FALSE));
                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }
}