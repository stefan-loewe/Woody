<?php

namespace ws\loewe\Woody\examples;

require_once(realpath(__DIR__.'/../../../source/bootstrap.php'));

use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;
use ws\loewe\Woody\App\Application;
use ws\loewe\Woody\Components\Controls\Calendar;
use ws\loewe\Woody\Components\Controls\Checkbox;
use ws\loewe\Woody\Components\Controls\ComboBox;
use ws\loewe\Woody\Components\Controls\EditArea;
use ws\loewe\Woody\Components\Controls\EditBox;
use ws\loewe\Woody\Components\Controls\Frame;
use ws\loewe\Woody\Components\Controls\HtmlControl;
use ws\loewe\Woody\Components\Controls\Label;
use ws\loewe\Woody\Components\Controls\ProgressBar;
use ws\loewe\Woody\Components\Controls\PushButton;
use ws\loewe\Woody\Components\Controls\ScrollBar;
use ws\loewe\Woody\Components\Controls\Slider;
use ws\loewe\Woody\Components\Controls\Spinner;
use ws\loewe\Woody\Components\Controls\Tab;
use ws\loewe\Woody\Components\Controls\Table;
use ws\loewe\Woody\Components\Controls\TreeView;
use ws\loewe\Woody\Components\Windows\ResizableWindow;
use ws\loewe\Woody\Event\WindowCloseAdapter;
use ws\loewe\Woody\Layouts\GridLayout;

/**
 * Description of ManyControlsApp
 *
 * @author Stefan Löwe
 */
class ManyControlsApp extends Application {

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    parent::__construct();

    $this->window = new ResizableWindow('ManyControlsApp',
      Point::createInstance(50, 50),
      Dimension::createInstance(550, 600));

    $this->window->create(null);

    $this->window->setWindowCloseListener(
      new WindowCloseAdapter(
        function($event) {
          $event->getSource()->close();
        }));

    // get the top level pane of the window to add controls
    $rootPane = $this->window->getRootPane();
    $rootPane->hide();

    $this->addControlsToFrame($rootPane);

    // add a frame beneath the controls ...
    $rootPane->add($frame = new Frame('frame with grid layout',
      Point::createInstance(15, 100),
      Dimension::createInstance(500, 80)));

    // ... add the same type of controls ...
    $this->addControlsToFrame($frame);

    // ... but layout them automatically with a 2 x 5 grid layout
    // with 15 and 20 pixels as vertical and horizontal gaps, respectively
    $layout = new GridLayout(2, 5, 15, 20);
    $frame->setLayout($layout);
    $layout->layout($frame);

    $mainTab = new Tab(Point::createInstance(15, 200), Dimension::createInstance(500, 300));
    $rootPane->add($mainTab);
    $mainTab->addTabPage('Simple');
    $mainTab->addTabPage('Advanced');
    $mainTab->addTabPage('Expert');

    $generalTab = $mainTab->getTabPage('Simple');
    $this->addControlsToFrame($generalTab);

    // add a frame beneath the controls ...
    $generalTab->add($frame = new Frame('frame with grid layout',
      Point::createInstance(15, 100),
      Dimension::createInstance(460, 80)));

    // ... add the same type of controls ...
    $this->addControlsToFrame($frame);

    // ... but layout them automatically with a 2 x 5 grid layout
    // with 15 and 20 pixels as vertical and horizontal gaps, respectively
    $frame->setLayout($layout);
    $layout->layout($frame);

    $advancedTab = $mainTab->getTabPage('Advanced');
    $advancedTab->add($frmAdvanced1 = new Frame('frame containing a calendar',
      Point::createInstance(15, 15),
      Dimension::createInstance(200, 200)));
    $frmAdvanced1->add(new Calendar(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));
    $advancedTab->add($frmAdvanced2 = new Frame('frame containing a website',
      Point::createInstance(225, 15),
      Dimension::createInstance(200, 200)));
    $frmAdvanced2->add(new HtmlControl('http://www.github.com', Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));

    $advancedTab->add(new ScrollBar(Point::createInstance(475, 15),
      Dimension::createInstance(15, 225)));
    $advancedTab->add(new ScrollBar(Point::createInstance(15, 250),
      Dimension::createInstance(475, 15)));

    $expertTab = $mainTab->getTabPage('Expert');
    $expertTab->add($frmExpert1 = new Frame('frame containing a tree view',
      Point::createInstance(15, 15),
      Dimension::createInstance(200, 200)));
    $frmExpert1->add(new TreeView(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));
    $expertTab->add($frmExpert2 = new Frame('frame containing a table',
      Point::createInstance(225, 15),
      Dimension::createInstance(200, 200)));
    $frmExpert2->add(new Table(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));
/*

    $btn1->addActionListener(new ActionAdapter(function(Event $event) {
      echo $event->getSource()->getLabel();
    }));
    $btn1->addActionListener(new ActionAdapter(function(Event $event) {
      echo $event->getSource()->getLabel();
    }));
    $btn2->addActionListener(new ActionAdapter(function(Event $event) {
      echo $event->getSource()->getLabel();
    }));

    $sld->addActionListener(new ActionAdapter(function(Event $event) {
      echo $event->getSource()->getValue();
    }));
    $scb->addActionListener(new ActionAdapter(function(Event $event) {
      echo $event->getSource()->getOffset();
    }));
*/
    $mainTab->setFocus('Expert');
    $rootPane->show();
  }

  private function addControlsToFrame(Frame $rootPane) {
    $rootPane->add($lbl1 = new Label("label #1",
          Point::createInstance(15, 15),
          Dimension::createInstance(100, 25)));
        $rootPane->add($edb1 = new EditBox("edit box #1",
          Point::createInstance(125, 15),
          Dimension::createInstance(100, 25)));
        $rootPane->add($btn1 = new PushButton("push button #1",
          Point::createInstance(235, 15),
          Dimension::createInstance(100, 25)));
        $rootPane->add($eda1 = new EditArea("edit area #1",
          Point::createInstance(345, 15),
          Dimension::createInstance(100, 59)));
        $rootPane->add($chk = new Checkbox(true,
          Point::createInstance(455, 15),
          Dimension::createInstance(100, 25)));

        $rootPane->add($sld1 = new Slider(Point::createInstance(15, 50),
          Dimension::createInstance(100, 25)));
        $rootPane->add($pgb = new ProgressBar(Point::createInstance(125, 50),
          Dimension::createInstance(100, 25)));
        $rootPane->add($cbb1 = new ComboBox(Point::createInstance(235, 50),
          Dimension::createInstance(100, 25)));
        $rootPane->add($spn1 = new Spinner(Point::createInstance(455, 50),
          Dimension::createInstance(100, 25)));
  }

  public function start() {
    $this->window->startEventHandler();

    return $this;
  }

  public function stop() {

  }
}

$app = new ManyControlsApp();
$app->start();