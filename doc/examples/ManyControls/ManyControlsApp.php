<?php

namespace ws\loewe\Woody\examples;

require_once(realpath(__DIR__.'/../../../source/bootstrap.php'));

use DateTime;
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
use ws\loewe\Woody\Event\ActionAdapter;
use ws\loewe\Woody\Event\Event;
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

    // call the constructor of the ResizableWindow
    $this->window = new ResizableWindow('ManyControlsApp',
      Point::createInstance(50, 50),
      Dimension::createInstance(550, 600));

    // actually create the window
    $this->window->create(null);

    // add the default window close handler
    $this->window->setWindowCloseListener(
      new WindowCloseAdapter(
        function($event) {
          $event->getSource()->close();
        }));

    // get the top-level pane of the window to add controls
    $rootPane = $this->window->getRootPane();

    // hide the pane for now
    $rootPane->hide();

    // add several controls to the top-level pane
    $this->addControlsToFrame($rootPane);

    // add several controls to the top-level pane, with a layout
    $this->addControlsToFrameWithLayout($rootPane);

    // add a tab-pane to the top-level pane
    $mainTab = $this->createMainTab($rootPane);

    // setup the simple tab
    $this->setupSimpleTab($mainTab->getTabPage('Simple'));

    // setup the advanced tab
    $this->setupAdvancedTab($mainTab->getTabPage('Advanced'));

    // setup the expert tab
    $this->setupExpertTab($mainTab->getTabPage('Expert'));

    // add a status bar (yes, a Woody class will be added for that, too)
    $statusbar = wb_create_control($this->window->getControlID(), StatusBar, '');

    // add the action listeners
    $this->addActionListener($rootPane, $statusbar);

    // finally, show the window
    $rootPane->show();
  }

  public function start() {
    $this->window->startEventHandler();

    return $this;
  }

  public function stop() {
  }

  private function addControlsToFrame(Frame $parent) {
    $parent->add(new Label("label #1",
      Point::createInstance(15, 15),
      Dimension::createInstance(100, 25)));
    $parent->add(new EditBox("edit box #1",
      Point::createInstance(125, 15),
      Dimension::createInstance(100, 25)));
    $parent->add(new PushButton("push button #1",
      Point::createInstance(235, 15),
      Dimension::createInstance(100, 25)));
    $parent->add(new EditArea("edit area #1",
      Point::createInstance(345, 15),
      Dimension::createInstance(100, 59)));
    $parent->add(new Checkbox(true,
      Point::createInstance(455, 15),
      Dimension::createInstance(100, 25)));

    $parent->add(new Slider(Point::createInstance(15, 50),
      Dimension::createInstance(100, 25)));
    $parent->add(new ProgressBar(Point::createInstance(125, 50),
      Dimension::createInstance(100, 25)));
    $parent->add(new ComboBox(Point::createInstance(235, 50),
      Dimension::createInstance(100, 25)));
    $parent->add(new Spinner(Point::createInstance(455, 50),
      Dimension::createInstance(100, 25)));
  }

  private function addControlsToFrameWithLayout(Frame $parent) {
    // add a frame first ...
    $parent->add($frame = new Frame('frame with grid layout',
      Point::createInstance(15, 100),
      Dimension::createInstance($parent->getDimension()->width * 0.90, 80)));

    // ... add the default controls to the frame ...
    $this->addControlsToFrame($frame);

    // ... but layout them automatically on a 2 x 5 grid
    // with 15 and 20 pixels as vertical and horizontal gaps, respectively
    $layout = new GridLayout(2, 5, 15, 20);
    $frame->setLayout($layout);
    $layout->layout($frame);
  }

  private function createMainTab(Frame $rootPane) {
    // create and add a tab ...
    $mainTab = new Tab(Point::createInstance(15, 200), Dimension::createInstance(500, 350));
    $rootPane->add($mainTab);

    // ... add three tab pages with the repective titles ...
    $mainTab->addTabPage('Simple');
    $mainTab->addTabPage('Advanced');
    $mainTab->addTabPage('Expert');

    // ... set the focus to the advanced tab
    $mainTab->setFocus('Advanced');

    return $mainTab;
  }

  private function setupSimpleTab(Frame $simpleTab) {
    // add the default controls ...
    $this->addControlsToFrame($simpleTab);

    // ... again withing in a frame, with grid layout ...
    $this->addControlsToFrameWithLayout($simpleTab);

    // ... and create another tab within the tab
    $childTab = $this->createChildTab($simpleTab);

    // setup the 1st child tab
    $this->setup1stChildTab($childTab);

    // setup the 2nd child tab
    $this->setup2ndChildTab($childTab);

    // setup the 1st grandchild tab
    $this->setup1stGrandChildTab($childTab->getTabPage('Page #1'));

    // setup the 2nd grandchild tab
    $this->setup2ndGrandChildTab($childTab->getTabPage('Page #2'));
  }

  private function createChildTab(Frame $simpleTab) {
    $childTab = new Tab(Point::createInstance(15, 195), Dimension::createInstance(460, 130));
    $simpleTab->add($childTab);
    $childTab->addTabPage('Page #1');
    $childTab->addTabPage('Page #2');

    return $childTab;
  }

  private function setup1stChildTab($childTab) {
    $page1Tab = $childTab->getTabPage('Page #1');
    $page1Tab->add(new PushButton("tabbed button #1",
      Point::createInstance(15, 15),
      Dimension::createInstance(100, 25)));

    $page1Tab->add(new Checkbox(true,
      Point::createInstance(125, 15),
      Dimension::createInstance(100, 25)));
  }

  private function setup2ndChildTab($childTab) {
    $page2Tab = $childTab->getTabPage('Page #2');
    $page2Tab->add(new PushButton("tabbed button #2",
      Point::createInstance(15, 15),
      Dimension::createInstance(100, 25)));

    $page2Tab->add(new Checkbox(false,
      Point::createInstance(125, 15),
      Dimension::createInstance(100, 25)));
  }

  private function setup1stGrandChildTab(Frame $page1Tab) {
    $grandChildTab1 = new Tab(Point::createInstance(15, 50), Dimension::createInstance(420, 60));
    $page1Tab->add($grandChildTab1);

    $grandChildTab1->addTabPage('Page #1.1');
    $grandChildTab1->addTabPage('Page #1.2');

    $page1_1Tab = $grandChildTab1->getTabPage('Page #1.1');
    $page1_1Tab->add(new Checkbox(false,
      Point::createInstance(15, 15),
      Dimension::createInstance(100, 25)));

    $page1_2Tab = $grandChildTab1->getTabPage('Page #1.2');
    $page1_2Tab->add(new EditBox('edit box on Page #1.2',
      Point::createInstance(15, 15),
      Dimension::createInstance(390, 25)));
  }

  private function setup2ndGrandChildTab(Frame $page2Tab) {
    $grandChildTab2 = new Tab(Point::createInstance(15, 50), Dimension::createInstance(420, 60));
    $page2Tab->add($grandChildTab2);

    $grandChildTab2->addTabPage('Page #2.1');
    $grandChildTab2->addTabPage('Page #2.2');

    $page2_1Tab = $grandChildTab2->getTabPage('Page #2.1');
    $page2_1Tab->add(new Slider(
      Point::createInstance(15, 15),
      Dimension::createInstance(390, 25)));

    $page2_2Tab = $grandChildTab2->getTabPage('Page #2.2');
    $page2_2Tab->add(new Scrollbar(
      Point::createInstance(15, 15),
      Dimension::createInstance(390, 25)));
  }

  private function setupAdvancedTab(Frame $advancedTab) {
    // add a calendar control in a frame ...
    $advancedTab->add($frmAdvanced1 = new Frame('frame containing a calendar',
      Point::createInstance(15, 15),
      Dimension::createInstance(200, 200)));
    $frmAdvanced1->add(new Calendar(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));

    // ... and a website control in a frame ...
    $advancedTab->add($frmAdvanced2 = new Frame('frame containing a website',
      Point::createInstance(225, 15),
      Dimension::createInstance(200, 200)));
    $frmAdvanced2->add(new HtmlControl('http://www.github.com', Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));

    // ... as well as a vertical and a horizontal scroll bar
    $advancedTab->add(new ScrollBar(Point::createInstance(475, 15),
      Dimension::createInstance(15, 225)));
    $advancedTab->add(new ScrollBar(Point::createInstance(15, 250),
      Dimension::createInstance(475, 15)));
  }

  private function setupExpertTab(Frame $expertTab) {
    // add a treeview control in a frame ...
    $expertTab->add($frmExpert1 = new Frame('frame containing a tree view',
      Point::createInstance(15, 15),
      Dimension::createInstance(200, 200)));
    $frmExpert1->add(new TreeView(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));

    // ... and a table control in a frame
    $expertTab->add($frmExpert2 = new Frame('frame containing a table',
      Point::createInstance(225, 15),
      Dimension::createInstance(200, 200)));
    $frmExpert2->add(new Table(Point::createInstance(15, 15),
      Dimension::createInstance(175, 175)));
  }

  private function addActionListener(Frame $parent, $statusbar) {

    foreach($parent->getComponents() as $component) {
      // recursively add action listener to controls in frames
      if($component instanceof Frame) {
        $this->addActionListener($component, $statusbar);
        continue;
      }

      // recursively add action listener to controls in tab pages
      else if($component instanceof Tab) {
        foreach($component->getTabPages() as $frame) {
          $this->addActionListener($frame, $statusbar);
        }
        continue;
      }

      // add action listener to simple controls
      $component->addActionListener(new ActionAdapter(function(Event $event) use ($statusbar) {
        wb_set_text($statusbar, ((new DateTime())->format('H:i:s')).
          ': source of event is '.(new \ReflectionClass(get_class($event->getSource())))->getShortName().
          ' with id '.$event->getSource()->getControlID());
      }));
    }
  }
}

$app = new ManyControlsApp();
$app->start();