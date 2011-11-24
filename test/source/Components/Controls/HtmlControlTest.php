<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;

/**
 * Test class for HtmlControl.
 * Generated by PHPUnit on 2011-11-24 at 17:24:08.
 */
class HtmlControlTest extends \PHPUnit_Framework_TestCase {

    /**
     * the html control to test
     *
     * @var HtmlControl
     */
    protected $htmlControl  = null;

    /**
     * the test application
     *
     * @var \Woody\App\TestApplication
     */
    private $application    = false;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->application = new TestApplication();

        $this->htmlControl = new HtmlControl('http://www.web.de', new Point(20, 20), new Dimension(260, 160));

        $this->application->getWindow()->add($this->htmlControl);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * This method tests getting the URL of the html control.
     *
     * @covers \Woody\Components\Controls\Timer::setUrl
     */
    public function testGetUrl()
    {
        $this->timer = new Timer(function()
                        {
                            $this->assertEquals('http://www.web.de', $this->htmlControl->getUrl());
                            $this->timer->destroy();

                            $this->application->stop();
                        }, $this->application->getWindow(), 100);

        $this->timer->start($this->application->getWindow());

        $this->application->start();
    }

    /**
     * This method tests setting the URL of the html control.
     *
     * @covers \Woody\Components\Controls\Timer::setUrl
     */
    public function testSetUrl() {
        $this->timer = new Timer(function()
                        {
                            $url = 'http:\\www.php.net';
                            $this->htmlControl->setUrl($url);
                            $this->assertEquals($url, $this->htmlControl->getUrl());
                            $this->timer->destroy();

                            $this->application->stop();
                        }, $this->application->getWindow(), 100);

        $this->timer->start($this->application->getWindow());

        $this->application->start();
    }
}