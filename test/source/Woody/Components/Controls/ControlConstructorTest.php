<?php

namespace Woody\Components\Controls;

use \Woody\Components\Windows\MainWindow;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for PushButton.
 * Generated by PHPUnit on 2011-12-15 at 22:14:10.
 */
class ControlConstructorTest extends \PHPUnit_Framework_TestCase {
  /**
   * the control to test
   *
   * @var \Woody\Components\Controls\Control
   */
  private $control = null;

  /**
   * the window to hold the control
   *
   * @var \Woody\Components\Controls\Windows\AbstractWindow
   */
  private $window = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->window = new MainWindow('ControlConstructTest', new Point(10, 10), new Dimension(300, 200));
    $this->window->create();
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    $this->window->destroy();
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Calendar::__construct
   */
  public function testConstructCalendar() {
    $this->control = new Calendar(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Checkbox::__construct
   */
  public function testConstructCheckbox() {
    $this->control = new Checkbox(FALSE, new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\ListControl::__construct
   * @covers \Woody\Components\Controls\ComboBox::__construct
   */
  public function testConstructComboBox() {
    $this->control = new ComboBox(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\EditField::__construct
   * @covers \Woody\Components\Controls\EditBox::__construct
   */
  public function testConstructEditBox() {
    $this->control = new EditBox('testConstructEditBox', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\EditField::__construct
   * @covers \Woody\Components\Controls\EditArea::__construct
   */
  public function testConstructEditArea() {
    $this->control = new EditArea('testConstructEditArea', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Frame::__construct
   * @covers \Woody\Components\Controls\Frame::create
   */
  public function testConstructFrame() {
    $this->control = new Frame('testConstructFrame', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());

    $this->window->getRootPane()->add($this->control);
    $this->assertEquals($this->window->getRootPane(), $this->control->getParent());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\HtmlControl::__construct
   * @covers \Woody\Components\Controls\HtmlControl::create
   */
  public function testConstructHtmlControl() {
    $this->control = new HtmlControl('http://www.loewe.ws', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());

    $this->window->getRootPane()->add($this->control);
    $this->assertEquals($this->window->getRootPane(), $this->control->getParent());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Image::__construct
   * @covers \Woody\Components\Controls\Image::create
   * @covers \Woody\Components\Controls\Image::setImage
   */
  public function testConstructImage() {
    $imageResource = $this->getMockBuilder('\Woody\Util\Image\ImageResource')->disableOriginalConstructor()->getMock();
    $this->control = new Image($imageResource, new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());

    $imageResource->expects($this->once())
      ->method('getResource')
      ->will($this->returnValue(\Woody\Util\Image\ImageResource::create(new Dimension(10, 10))->getResource()));

    $this->window->getRootPane()->add($this->control);
    $this->assertEquals($this->window->getRootPane(), $this->control->getParent());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Button::__construct
   * @covers \Woody\Components\Controls\ImageButton::__construct
   * @covers \Woody\Components\Controls\ImageButton::create
   * @covers \Woody\Components\Controls\ImageButton::setImage
   */
  public function testConstructImageButton() {
    $imageResource = $this->getMockBuilder('\Woody\Util\Image\ImageResource')->disableOriginalConstructor()->getMock();
    $imageResource->expects($this->once())
      ->method('getResource')
      ->will($this->returnValue(\Woody\Util\Image\ImageResource::create(new Dimension(10, 10))->getResource()));

    $this->control = new ImageButton($imageResource, new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());

    $this->window->getRootPane()->add($this->control);
    $this->assertEquals($this->window->getRootPane(), $this->control->getParent());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\InvisibleArea::__construct
   */
  public function testConstructInvisibleArea() {
    $this->control = new InvisibleArea(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Label::__construct
   */
  public function testConstructLabel() {
    $this->control = new Label('testConstructLabel', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\ListControl::__construct
   * @covers \Woody\Components\Controls\ListControl::getDefaultCellRenderer
   * @covers \Woody\Components\Controls\ListBox::__construct
   */
  public function testConstructListBox() {
    $this->control = new ListBox(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\ProgressBar::__construct
   */
  public function testConstructProgressBar() {
    $this->control = new ProgressBar(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Button::__construct
   * @covers \Woody\Components\Controls\PushButton::__construct
   */
  public function testConstructPushButton() {
    $this->control = new PushButton('buttonConstruct', new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\ScrollBar::__construct
   */
  public function testConstructScrollBar() {
    $this->control = new ScrollBar(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Slider::__construct
   */
  public function testConstructSlider() {
    $this->control = new Slider(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Spinner::__construct
   */
  public function testConstructSpinner() {
    $this->control = new Spinner(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Tab::__construct
   */
  public function testConstructTab() {
    $this->control = new Tab(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\Table::__construct
   */
  public function testConstructTable() {
    $this->control = new Table(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }

  /**
   * @covers \Woody\Components\Component::__construct
   * @covers \Woody\Components\Controls\Control::__construct
   * @covers \Woody\Components\Controls\TreeView::__construct
   * @covers \Woody\Components\Controls\TreeView::getDefaultNodeRenderer
   */
  public function testConstructTreeView() {
    $this->control = new TreeView(new Point(20, 20), new Dimension(120, 20));
    $this->assertNotNull($this->control->getID());
  }
}