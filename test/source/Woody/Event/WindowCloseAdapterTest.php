<?php

namespace Woody\Event;

/**
 * Test class for WindowCloseAdapter.
 * Generated by PHPUnit on 2012-09-04 at 20:53:06.
 */
class WindowCloseAdapterTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var WindowCloseAdapter
   */
  private $adapter = null;

  /**
   * @var int simple counter to determine, how often the callback was executed
   */
  private $callbackExecuted = 0;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method test the constructor of the adapter
   *
   * @covers Woody\Event\WindowCloseAdapter::__construct
   */
  public function testConstruct() {
    $this->adapter = new WindowCloseAdapter(function(){});
    $this->assertInstanceOf('Woody\Event\WindowCloseAdapter', $this->adapter);
  }

  /**
   * @covers Woody\Event\WindowCloseAdapter::windowClosed
   */
  public function testWindowClosed() {
    $event = $this->getMockBuilder('Woody\Event\WindowCloseEvent')
      ->disableOriginalConstructor()
      ->getMock();

    $this->adapter = new WindowCloseAdapter(function(){$this->callbackExecuted = 1;});
    $this->assertEquals(0, $this->callbackExecuted);

    $this->adapter->windowClosed($event);
    $this->assertEquals(1, $this->callbackExecuted);
  }
}