<?php

namespace Woody\Components\Controls;

use \Woody\Model\DefaultTreeModel;
use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Utils\Tree\TreeNode;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for TreeView.
 * Generated by PHPUnit on 2011-12-18 at 19:31:01.
 */
class TreeViewTest extends \PHPUnit_Framework_TestCase {
  /**
   * the tree view to test
   *
   * @var \Woody\Components\Controls\Treeview
   */
  private $treeView = null;

  /**
   * the test application
   *
   * @var \Woody\App\TestApplication
   */
  private $application = false;

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

    $this->treeView = new TreeView(new Point(20, 20), new Dimension(260, 130));

    $this->application->getWindow()->getRootPane()->add($this->treeView);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * @covers \Woody\Components\Controls\TreeView::getSelectedItem
   * @covers \Woody\Components\Controls\TreeView::findNodeByHash
   * @covers \Woody\Components\Controls\TreeView::setSelectedItem
   */
  public function testGetSetSelectedItem() {
    $this->timer = new Timer(function() {
          $model = $this->getDefaultMock();
          $this->treeView->setModel($model);
          $this->assertNull($this->treeView->getSelectedItem());

          $this->treeView->setSelectedItem($model->getRoot());
          $this->assertSame($model->getRoot(), $this->treeView->getSelectedItem());

          $this->treeView->setSelectedItem($model->getRoot()->getChildAtIndex(1));
          $this->assertSame($model->getRoot()->getChildAtIndex(1), $this->treeView->getSelectedItem());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\TreeView::getParentItem
   */
  public function testGetParentItem() {
    $this->timer = new Timer(function() {
          $model = $this->getDefaultMock();
          $this->treeView->setModel($model);
          $this->assertNull($this->treeView->getParentItem());

          $this->treeView->setSelectedItem($model->getRoot());
          $this->assertNull($this->treeView->getParentItem());

          $this->treeView->setSelectedItem($model->getRoot()->getChildAtIndex(1));
          $this->assertSame($model->getRoot(), $this->treeView->getParentItem());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\TreeView::getModel
   * @covers \Woody\Components\Controls\TreeView::setModel
   */
  public function testGetSetModel() {
    $this->timer = new Timer(function() {
          $this->assertNull($this->treeView->getModel());

          $model = $this->getMockBuilder('\Woody\Model\DefaultTreeModel')
            ->disableOriginalConstructor()
            ->getMock();

          $this->treeView->setModel($model);
          $this->assertNotNull($this->treeView->getModel());
          $this->assertSame($model, $this->treeView->getModel());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\TreeView::update
   * @covers \Woody\Components\Controls\TreeView::clear
   * @covers \Woody\Components\Controls\TreeView::rebuild
   * @covers \Woody\Components\Controls\TreeView::addNode
   * @covers \Woody\Components\Controls\TreeView::getHash
   */
  public function testUpdate() {
    $this->timer = new Timer(function() {
          $model = $this->getDefaultMock();
          $this->treeView->setModel($model);
          $model->attach($this->treeView);
          $this->assertNull($this->treeView->getSelectedItem());

          $itemB = $model->getRoot()->getChildAtIndex(1);
          $this->treeView->setSelectedItem($itemB);
          $this->assertSame($itemB, $this->treeView->getSelectedItem());

          $model->appendChild($itemB, $itemC = new TreeNode('C'));
          $this->assertSame($itemB, $this->treeView->getSelectedItem());

          $this->treeView->setSelectedItem($itemC);
          $this->assertSame($itemC, $this->treeView->getSelectedItem());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests expanding and collapsing a node in the tree view.
   *
   * @covers \Woody\Components\Controls\TreeView::expandNode
   * @covers \Woody\Components\Controls\TreeView::collapseNode
   */
  public function testExpandCollapse() {
    $callback = function() {
      $model = $this->getDefaultMock();
      $this->treeView->setModel($model);

      $this->assertSame($this->treeView, $this->treeView->expandNode($model->getRoot()));
      $this->assertSame($this->treeView, $this->treeView->collapseNode($model->getRoot()));

      $this->timer->destroy();
      $this->application->stop();
    };

    $this->timer = new Timer($callback, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests setting the node renderer.
   *
   * @covers \Woody\Components\Controls\TreeView::setNodeRenderer
   */
  public function testSetNodeRenderer() {
    $callback = function() {
      $this->timer->destroy();
      $this->application->stop();
    };

    $this->timer = new Timer($callback, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->assertSame($this->treeView, $this->treeView->setNodeRenderer(function() {}));

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method returns the default mock object for testing this class.
   *
   * @return \Woody\Model\DefaultTreeModel
   */
  private function getDefaultMock() {
    $root = new TreeNode('root');

    $root->appendChild(new TreeNode('A'));
    $root->appendChild(new TreeNode('B'));

    return new DefaultTreeModel($root);
  }
}