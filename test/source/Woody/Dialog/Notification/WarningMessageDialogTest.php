<?php

namespace Woody\Dialog\Notification;

/**
 * Test class for WarningMessageDialog.
 * Generated by PHPUnit on 2012-07-02 at 23:11:10.
 */
class WarningMessageDialogTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var WarningMessageDialog
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
   * @covers \Woody\Dialog\Notification\WarningMessageDialog::__construct
   * @covers \Woody\Dialog\Notification\MessageDialog::__construct
   * @covers \Woody\Dialog\Notification\ModalSystemDialog::__construct
   */
  public function testConstruct() {
    $this->dialog = new WarningMessageDialog('testConstruct', 'testConstruct', null);

    $this->assertInstanceOf('\Woody\Dialog\Notification\WarningMessageDialog', $this->dialog);
  }
}