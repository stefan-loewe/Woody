<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;

/**
 * Test class for ListControl.
 * Generated by PHPUnit on 2011-12-06 at 15:55:32.
 */
abstract class ListControlTest extends \PHPUnit_Framework_TestCase {
  /**
   * the list control under test
   *
   * @var \Woody\Components\Controls\ListControl
   */
  protected $listControl = null;

  /**
   * the test application
   *
   * @var \Woody\App\TestApplication
   */
  protected $application = null;

  /**
   * the timer for the test application
   *
   * @var \Woody\Components\Timer\Timer
   */
  private $timer = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->listControl = $this->getObjectUnderTest();

    $this->application->getWindow()->getRootPane()->add($this->listControl);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::getModel
   * @covers \Woody\Components\Controls\ListControl::setModel
   */
  public function testGetSetModel() {
    $this->timer = new Timer(function() {
                              $this->assertNull($this->listControl->getModel());

                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();

                              $this->listControl->setModel($model);
                              $this->assertNotNull($this->listControl->getModel());
                              $this->assertSame($model, $this->listControl->getModel());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::update
   */
  public function testUpdate() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(-1, $this->listControl->getSelectedIndex());

                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              // ... returning 'A' on each call to getElementAt
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValue('A'));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(1));

                              $this->listControl->setModel($model);
                              $model->attach($this->listControl);
                              $this->assertEquals(-1, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(0);
                              $this->assertEquals('A', $this->listControl->getSelectedElement());

                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValue('B'));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(2));

                              $this->listControl->setModel($model);
                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals('B', $this->listControl->getSelectedElement());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::getSelectedIndex
   * @covers \Woody\Components\Controls\ListControl::setSelectedIndex
   * @covers \Woody\Components\Controls\ListControl::isValidIndex
   */
  public function testGetSetSelectedIndex() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(10);
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              // get a mock for the model ...
                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              // ... returning 'A' on each call to getElementAt
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValue('A'));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(2));

                              $model->attach($this->listControl);
                              $this->listControl->setModel($model);
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(0);
                              $this->assertEquals(0, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals(1, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals(1, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(2);
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals(1, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(ListControl::NO_SELECTION);
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(-2);
                              $this->assertEquals(ListControl::NO_SELECTION, $this->listControl->getSelectedIndex());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals(1, $this->listControl->getSelectedIndex());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::getSelectedElement
   */
  public function testGetSelectedElement() {
    $this->timer = new Timer(function() {
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(10);
                              $this->assertNull($this->listControl->getSelectedElement());

                              // get a mock for the model ...
                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              // ... returning 'A' on each call to getElementAt
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValueMap(array(array(0, 'A'), array(1, 'B'))));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(2));

                              $model->attach($this->listControl);
                              $this->listControl->setModel($model);
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals('B', $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(2);
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals('B', $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(-1);
                              $this->assertEquals(null, $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(1);
                              $this->assertEquals('B', $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(0);
                              $this->assertEquals('A', $this->listControl->getSelectedElement());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::clearSelection
   */
  public function testClearSelection() {
    $this->timer = new Timer(function() {
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->clearSelection();
                              $this->assertNull($this->listControl->getSelectedElement());

                              // get a mock for the model ...
                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              // ... returning 'A' on each call to getElementAt
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValueMap(array(array(0, 'A'), array(1, 'B'))));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(2));

                              $model->attach($this->listControl);
                              $this->listControl->setModel($model);
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedIndex(0);
                              $this->assertEquals('A', $this->listControl->getSelectedElement());

                              $this->listControl->clearSelection();
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->clearSelection();
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\ListControl::setSelectedElement
   */
  public function testSetSelectedElement() {
    $this->timer = new Timer(function() {
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedElement(null);
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedElement(2);
                              $this->assertNull($this->listControl->getSelectedElement());

                              // get a mock for the model ...
                              $model = $this->getMockBuilder('\Woody\Model\ListModel')
                                      ->disableOriginalConstructor()
                                      ->getMock();
                              // ... returning 'A' on each call to getElementAt
                              $model->expects($this->any())
                                      ->method('getElementAt')
                                      ->will($this->returnValueMap(array(array(0, 'A'), array(1, 'B'))));
                              $model->expects($this->any())
                                      ->method('getIndexOf')
                                      ->will($this->returnValueMap(array(array('A', 0), array('B', 1), array('C', -1))));
                              $model->expects($this->any())
                                      ->method('count')
                                      ->will($this->returnValue(2));

                              $model->attach($this->listControl);
                              $this->listControl->setModel($model);
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->listControl->setSelectedElement('A');
                              $this->assertEquals('A', $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedElement('B');
                              $this->assertEquals('B', $this->listControl->getSelectedElement());

                              $this->listControl->setSelectedElement('C');
                              $this->assertNull($this->listControl->getSelectedElement());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests setting the cell renderer.
   *
   * @covers \Woody\Components\Controls\ListControl::setCellRenderer
   */
  public function testSetCellRenderer() {
    $callback = function() {
      $this->timer->destroy();
      $this->application->stop();
    };

    $this->timer = new Timer($callback, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->assertSame($this->listControl, $this->listControl->setCellRenderer(function() {}));

    $this->timer->start();

    $this->application->start();
  }
}