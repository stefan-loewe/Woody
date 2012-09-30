<?php

namespace ws\loewe\Woody\Event;

/**
 * Test class for KeyAdapter.
 * Generated by PHPUnit on 2012-06-25 at 21:52:31.
 */
class KeyAdapterTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var KeyAdapter the adapter to test
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
  protected function setUp() {
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
   * @covers ws\loewe\Woody\Event\KeyAdapter::__construct
   */
  public function testConstruct() {
    $this->adapter = new KeyAdapter(null, null);
    $this->assertInstanceOf('ws\loewe\Woody\Event\KeyAdapter', $this->adapter);

    $this->adapter = new KeyAdapter(function(){}, function(){});
    $this->assertInstanceOf('ws\loewe\Woody\Event\KeyAdapter', $this->adapter);
  }

  /**
   * This method test the callback execution when a key is pressed and released.
   *
   * @covers ws\loewe\Woody\Event\KeyAdapter::keyPressed
   * @covers ws\loewe\Woody\Event\KeyAdapter::keyReleased
   */
  public function testKeyPressedKeyReleased() {
    $event = $this->getMockBuilder('\ws\loewe\Woody\Event\KeyEvent')
      ->disableOriginalConstructor()
      ->getMock();

    $this->adapter = new KeyAdapter(null, null);
    $this->assertEquals(0, $this->callbackExecuted);

    $this->adapter->keyPressed($event);
    $this->assertEquals(0, $this->callbackExecuted);

    $this->adapter->keyReleased($event);
    $this->assertEquals(0, $this->callbackExecuted);

    $this->adapter = new KeyAdapter(function(){$this->callbackExecuted = 1;}, function(){$this->callbackExecuted = 2;});
    $this->assertEquals(0, $this->callbackExecuted);

    $this->adapter->keyPressed($event);
    $this->assertEquals(1, $this->callbackExecuted);

    $this->adapter->keyReleased($event);
    $this->assertEquals(2, $this->callbackExecuted);
  }
}