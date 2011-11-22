<?php

namespace Woody\Components\Timer;

use \Woody\Components\Windows\MainWindow;
use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;

/**
 * Test class for Timer.
 * Generated by PHPUnit on 2011-11-16 at 21:19:10.
 */
class TimerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * the timer to test
     *
     * @var \Woody\Components\Timer\Timer
     */
    private $timer      = null;

    /**
     * the window to which the timer is bound to
     *
     * @var \Woody\Components\Windows\MainWindow
     */
    private $window     = null;

    /**
     * the counter for testing timer callback call-counts
     *
     * @var int
     */
    private $counter    = 0;

    /**
     * This method sets up a plain window for testing.
     */
    protected function setUp()
    {
        $this->window = new MainWindow('timer test', new Point(50, 50), new Dimension(300, 200));

        $this->window->create(null);

        $this->counter = 0;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * This method tests the starting of a timer by checking if the callback gets executed at least once after the timer has been started.
     *
     * @covers Woody\Components\Timer\Timer::start
     */
    public function testStart()
    {
        $this->timer = new Timer(function()
                        {
                            $this->timer->destroy();
                            $this->window->destroy();

                            $this->assertEquals(1, ++$this->counter);
                        }, $this->window, 100);

        $this->timer->start();

        $this->window->startEventHandler();
    }

    /**
     * This method tests the running of a timer. The timer is started, and destroyed after the tenth execution..
     *
     * @covers Woody\Components\Timer\Timer::run
     */
    public function testRun()
    {
        $this->timer = new Timer(function()
                        {
                            if(++$this->counter > 10)
                            {
                                $this->timer->destroy();
                                $this->window->destroy();
                                $this->assertEquals(11, $this->counter);
                            }
                        }, $this->window, 100);

        $this->timer->start();

        $this->window->startEventHandler();
    }

    /**
     * This method tests the destroying of the timer.
     *
     * @covers Woody\Components\Timer\Timer::destroy
     */
    public function testDestroy()
    {
        $this->timer = new Timer(function()
                        {
                            ++$this->counter;

                            $this->timer->destroy();
                            $this->window->destroy();

                            $this->assertEquals(1, $this->counter);
                        }, $this->window, 100);

        $this->timer->start();

        $this->window->startEventHandler();
    }

    /**
     * This method tests the returning of the timer id.
     *
     * @covers Woody\Components\Timer\Timer::getID
     */
    public function testGetID()
    {
        $this->timer = new Timer(function()
                        {
                            $this->assertEquals($this->window->getID() + 1, $this->timer->getID());

                            $this->timer->destroy();
                            $this->window->destroy();

                            $this->assertEquals($this->window->getID() + 1, $this->timer->getID());
                        }, $this->window, 100);

        $this->assertEquals($this->window->getID() + 1, $this->timer->getID());

        $this->timer->start();

        $this->assertEquals($this->window->getID() + 1, $this->timer->getID());

        $this->window->startEventHandler();

        $this->assertEquals($this->window->getID() + 1, $this->timer->getID());
    }
}