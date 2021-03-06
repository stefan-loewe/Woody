<?php

namespace ws\loewe\Woody\Dialog\FileSystem;

/**
 * Test class for FileOpenDialog.
 * Generated by PHPUnit on 2012-07-02 at 21:42:18.
 */
class FileOpenDialogTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var FileOpenDialog
   */
  private $dialog = null;

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
   * @covers \ws\loewe\Woody\Dialog\FileSystem\FileOpenDialog::__construct
   * @covers \ws\loewe\Woody\Dialog\FileSystem\FileSystemDialog::__construct
   */
  public function testConstruct() {
    $this->dialog = new FileOpenDialog('testConstruct', null, '.', null);

    $this->assertInstanceOf('\ws\loewe\Woody\Dialog\FileSystem\FileOpenDialog', $this->dialog);
  }

  /**
   * This method tests getting the current selection from the select-file dialog.
   *
   * @covers \ws\loewe\Woody\Dialog\FileSystem\FileSystemDialog::getSelection
   */
  public function testGetSelection() {
    $this->dialog = new FileOpenDialog('testFileSelection', null, '.', null);

    $this->assertNull($this->dialog->getSelection());
  }
}