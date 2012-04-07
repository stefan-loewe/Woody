<?php
namespace Woody\Components\Windows;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\System\WindowConstraints;

/**
 * Test class for AbstractWindow.
 * Generated by PHPUnit on 2010-11-25 at 20:49:16.
 */
class AbstractWindowTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var AbstractWindow
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->delay = 0;

        $this->object = new MainWindow('MyWin2', new Point(50, 50), new Dimension(300, 200));

        $this->object->create(null);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
       $this->object->destroy();
    }

    public function testMoveBy() {
        $this->object->moveBy(new Dimension(10, 10));
        $this->assertEquals(60, $this->object->getPosition()->x);
        $this->assertEquals(60, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveBy(new Dimension(100, 300));
        $this->assertEquals(160, $this->object->getPosition()->x);
        $this->assertEquals(360, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveBy(new Dimension(-300, 300));
        $this->assertEquals(0, $this->object->getPosition()->x);
        $this->assertEquals(660, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveBy(new Dimension(140, -660));
        $this->assertEquals(140, $this->object->getPosition()->x);
        $this->assertEquals(0, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveBy(new Dimension(300, 200));
        $this->assertEquals(440, $this->object->getPosition()->x);
        $this->assertEquals(200, $this->object->getPosition()->y);
usleep($this->delay);
    }

    public function testMoveTo() {
        $this->object->moveTo(new Point(0, 0));
        $this->assertEquals(0, $this->object->getPosition()->x);
        $this->assertEquals(0, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveTo(new Point(400, 300));
        $this->assertEquals(400, $this->object->getPosition()->x);
        $this->assertEquals(300, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveTo(new Point(4000, 3000));
        $this->assertEquals(4000, $this->object->getPosition()->x);
        $this->assertEquals(3000, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveTo(new Point(-400, -300));
        $this->assertEquals(0, $this->object->getPosition()->x);
        $this->assertEquals(0, $this->object->getPosition()->y);
usleep($this->delay);
        $this->object->moveTo(new Point(0, 0));
        $this->assertEquals(0, $this->object->getPosition()->x);
        $this->assertEquals(0, $this->object->getPosition()->y);
    }

    public function testResizeBy() {
        $this->object->resizeBy(new Dimension(0, 0));
        $this->assertEquals(300, $this->object->getDimension()->width);
        $this->assertEquals(200, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeBy(new Dimension(100, 200));
        $this->assertEquals(400, $this->object->getDimension()->width);
        $this->assertEquals(400, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeBy(new Dimension(500, 600));
        $this->assertEquals(900, $this->object->getDimension()->width);
        $this->assertEquals(1000, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeBy(new Dimension(-878, -966));
        $this->assertEquals(WindowConstraints::getInstance()->minWidth, $this->object->getDimension()->width);
        $this->assertEquals(WindowConstraints::getInstance()->minHeight, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeBy(new Dimension(-200, -100));
        $this->assertEquals(WindowConstraints::getInstance()->minWidth, $this->object->getDimension()->width);
        $this->assertEquals(WindowConstraints::getInstance()->minHeight, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeBy(new Dimension(177, 166));
        $this->assertEquals(300, $this->object->getDimension()->width);
        $this->assertEquals(204, $this->object->getDimension()->height);
    }

    public function testResizeTo() {
        $this->object->resizeTo(new Dimension(300, 200));
        $this->assertEquals(300, $this->object->getDimension()->width);
        $this->assertEquals(200, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeTo(new Dimension(600, 400));
        $this->assertEquals(600, $this->object->getDimension()->width);
        $this->assertEquals(400, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeTo(new Dimension(0, 0));
        $this->assertEquals(WindowConstraints::getInstance()->minWidth, $this->object->getDimension()->width);
        $this->assertEquals(WindowConstraints::getInstance()->minHeight, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeTo(new Dimension(-100, -200));
        $this->assertEquals(WindowConstraints::getInstance()->minWidth, $this->object->getDimension()->width);
        $this->assertEquals(WindowConstraints::getInstance()->minHeight, $this->object->getDimension()->height);
usleep($this->delay);
        $this->object->resizeTo(new Dimension(300, 200));
        $this->assertEquals(300, $this->object->getDimension()->width);
        $this->assertEquals(200, $this->object->getDimension()->height);
    }
}