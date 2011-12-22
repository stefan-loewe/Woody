<?php

namespace Woody\Components\Controls;

use \Woody\Model\ITreeModel;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class TreeView extends Control implements \SplObserver {
    /**
     * the model of the tree view
     *
     * @var TreeModel
     */
    protected $model        = null;

    /**
     * the node renderer of the tree view
     *
     * @var callable
     */
    protected $nodeRenderer      = null;

    /**
     * the array of winbinder tree view node controls
     *
     * @var array[int]int
     */
    protected $controlNodes = array();

    /**
     * the style identifier that makes the tree view to draw lines between parent and child node
     */
    const DRAW_LINES        = WBC_LINES;

    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the list control
     * @param Dimension $dimension the dimension of the list control
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->type         = TreeView;

        $this->nodeRenderer = static::getDefaultNodeRenderer();
    }

    /**
     * This method returns the default item renderer for the tree view, which returns the result of __toString for objects implementing it and the plain passed in element if not.
     *
     * @todo copied from list model
     * @return \Callable the default cell renderer
     */
    protected static function getDefaultNodeRenderer() {
        $callback = function($element) {
                        if(is_callable(array($element, '__toString'), FALSE)) {
                            return $element->__toString();
                        } else {
                            return $element;
                        }
                    };

        return $callback;
    }

    protected function findNode($nodeHash) {
        $root   = $this->model->getRoot();
        $todo[] = $root;

        while(count($todo) > 0) {
            $currentNode = array_shift($todo);

            if($nodeHash == spl_object_hash($currentNode)) {
                return $currentNode;
            } else {
                for($i = 0, $count = $this->model->getChildCount($currentNode); $i < $count; $i++) {
                    $todo[] = $this->model->getChild($currentNode, $i);
                }
            }
        }

        return null;
    }

    public function getParentValue() {
        $parent = wb_get_parent($this->controlID, wb_get_selected($this->controlID));

        foreach($this->controlNodes as $hash => $nodeControlID) {
            if($nodeControlID == $parent) {
                return $this->findNode($hash);
            }
        }

        return null;
    }

    /**
     * This method returns the object associated with the currently selected tree view node.
     *
     * @return Object
     */
    public function getSelectedItem() {
        return $this->findNode(wb_get_value($this->controlID));
    }

    public function setSelectedItem($newValue) {
        wb_set_selected($this->controlID, $this->controlNodes[spl_object_hash($newValue)]);

        return $this;
    }

    /**
     * This method returns the tree model of the tree view.
     *
     * @return \Woody\Model\ITreeModel
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * This method sets the tree model of the tree view.
     *
     * @param \Woody\Model\ITreeModel $model the model to set
     * @return \Woody\Components\Controls\ListControl $this
     */
    public function setModel(ITreeModel $model) {
        return $this->update($model);
    }

    /**
     * This method sets the cell renderer of the tree view.
     *
     * @param \Callable $nodeRenderer
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function setNodeRenderer(callable $nodeRenderer) {
        $this->nodeRenderer = $nodeRenderer;

        return $this;
    }

    public function update(\SplSubject $treeModel) {
        $this->model = $treeModel;

        $selectedItem = $this->getValue();

        $this->clear();

        $root	= $this->model->getRoot();
        $todo[] = $root;

        $this->addNode($root, null);

        while(count($todo) > 0) {
            $currentNode = array_shift($todo);

            for($i = 0, $count = $this->model->getChildCount($currentNode); $i < $count; $i++) {
                $todo[] = ($node = $this->model->getChild($currentNode, $i));

                $this->addNode($node, $this->controlNodes[spl_object_hash($currentNode)]);
            }
        }

        if($selectedItem !== null) {
            $this->setValue($selectedItem);
        }

        return $this;
    }

    protected function addNode($newNode, $parentControlID) {
        $value = $this->nodeRenderer->__invoke($newNode);

        if($value !== null) {
            $data = array (
                        array (
                            $value,
                            spl_object_hash($newNode),
                            $parentControlID,
                            0,
                            0,
                            2
                        )
                    );

            $this->controlNodes[spl_object_hash($newNode)] = wb_create_items($this->controlID, $data);
        }

        return $this;
    }

    public function expandNode($value) {
        $hash = spl_object_hash($value);

        if(isset($this->controlNodes[$hash])) {
            $currentNodeID = $this->controlNodes[$hash];

            while($currentNodeID != 0) {
                wb_set_state($this->controlID, $currentNodeID, TRUE);
                $currentNodeID = wb_get_parent($this->controlID, $currentNodeID);
            }
        }

        return $this;
    }

    public function collapseNode($value) {
        $hash = spl_object_hash($value);

        if(isset($this->controlNodes[$hash])) {
            wb_set_state($this->controlID, $this->controlNodes[$hash], FALSE);
        }

        return $this;
    }

    final protected function clear() {
        wb_delete_items($this->controlID, null);
    }
}