<?php

namespace ws\loewe\Woody\Dialog\Notification;

/**
 * Test class for YesNoCancelConfirmationDialog.
 * Generated by PHPUnit on 2012-07-02 at 23:19:38.
 */
class YesNoCancelConfirmationDialogTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var YesNoCancelConfirmationDialog
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
   * @covers \ws\loewe\Woody\Dialog\Notification\YesNoCancelConfirmationDialog::__construct
   * @covers \ws\loewe\Woody\Dialog\Notification\ConfirmationDialog::__construct
   * @covers \ws\loewe\Woody\Dialog\Notification\ModalSystemDialog::__construct
   */
  public function testConstruct() {
    $this->dialog = new YesNoCancelConfirmationDialog('testConstruct', 'testConstruct', null);

    $this->assertInstanceOf('\ws\loewe\Woody\Dialog\Notification\YesNoCancelConfirmationDialog', $this->dialog);
  }

  /**
   * This method tests getting the initial state of the dialog.
   *
   * @covers \ws\loewe\Woody\Dialog\Notification\YesNoCancelConfirmationDialog::yes
   * @covers \ws\loewe\Woody\Dialog\Notification\YesNoCancelConfirmationDialog::no
   * @covers \ws\loewe\Woody\Dialog\Notification\YesNoCancelConfirmationDialog::cancel
   */
  public function testYesNoCancel() {
    $this->dialog = new YesNoCancelConfirmationDialog('testConstruct', 'testConstruct', null);

    $this->assertFalse($this->dialog->yes());
    $this->assertFalse($this->dialog->no());
    $this->assertFalse($this->dialog->cancel());
  }
}