<?php

namespace ws\loewe\Woody\Dialog\Notification;

/**
 * Test class for InfoMessageDialog.
 * Generated by PHPUnit on 2012-07-02 at 23:11:09.
 */
class InfoMessageDialogTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var InfoMessageDialog
   */
  protected $dialog;

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
   * This method tests creating the dialog.
   *
   * @covers \ws\loewe\Woody\Dialog\Notification\InfoMessageDialog::__construct
   * @covers \ws\loewe\Woody\Dialog\Notification\MessageDialog::__construct
   * @covers \ws\loewe\Woody\Dialog\Notification\ModalSystemDialog::__construct
   */
  public function testConstruct() {
    $this->dialog = new InfoMessageDialog('testConstruct', 'testConstruct', null);

    $this->assertInstanceOf('\ws\loewe\Woody\Dialog\Notification\InfoMessageDialog', $this->dialog);
  }
}