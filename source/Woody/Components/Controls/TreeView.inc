<?php

namespace Woody\Components\Controls;

use \Utils\Model\ITreeModel;
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
     * This method returns the object associated with the currently selected tree view node.
     *
     * @return object the currently selected element
     */
    public function getSelectedItem() {
        return $this->findNodeByHash(wb_get_value($this->controlID));
    }

    /**
     * This method returns the parent node of the object associated with the currently selected tree view node.
     *
     * @return object the parent of the currently selected tree view node
     */
    public function getParentItem() {
        $parent = wb_get_parent($this->controlID, wb_get_selected($this->controlID));

        foreach($this->controlNodes as $hash => $nodeControlID) {
            if($nodeControlID == $parent) {
                return $this->findNodeByHash($hash);
            }
        }

        return null;
    }

    /**
     * This method sets the given item as the selected item.
     *
     * @param object $item the item to set
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function setSelectedItem($item) {
        wb_set_selected($this->controlID, $this->controlNodes[self::getHash($item)]);

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
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function setModel(ITreeModel $model) {
        return $this->update($model);
    }

    /**
     * This method sets the cell renderer of the tree view.
     *
     * @param \callable $nodeRenderer
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function setNodeRenderer(callable $nodeRenderer) {
        $this->nodeRenderer = $nodeRenderer;

        return $this;
    }

    /**
     * This method expands the node associated with the given item.
     *
     * @param object $item the item whose node to expand
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function expandNode($item) {
        $hash = self::getHash($item);

        if(isset($this->controlNodes[$hash])) {
            $currentNodeID = $this->controlNodes[$hash];

            while($currentNodeID != 0) {
                wb_set_state($this->controlID, $currentNodeID, TRUE);
                $currentNodeID = wb_get_parent($this->controlID, $currentNodeID);
            }
        }

        return $this;
    }

    /**
     * This method collapses the node associated with the given item.
     *
     * @param object $item the item whose node to collapse
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function collapseNode($item) {
        $hash = self::getHash($item);

        if(isset($this->controlNodes[$hash])) {
            wb_set_state($this->controlID, $this->controlNodes[$hash], FALSE);
        }

        return $this;
    }

    /**
     * This method updates the tree view with the data from the given model.
     *
     * @param \SplSubject $treeModel the tree model which contains the data to display
     * @return \Woody\Components\Controls\TreeView $this
     */
    public function update(\SplSubject $treeModel) {
        $this->model    = $treeModel;
        $selectedItem   = $this->getSelectedItem();

        $this->clear()->rebuild();

        if($selectedItem !== null) {
            $this->setSelectedItem($selectedItem);
        }

        return $this;
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

    /**
     * This method clears all entries from the view.
     *
     * @return \Woody\Components\Controls\TreeView $this
     */
    protected function clear() {
        wb_delete_items($this->controlID, null);

        return $this;
    }

    /**
     * This method rebuilds the view.
     *
     * @return \Woody\Components\Controls\TreeView $this
     */
    protected function rebuild() {
        $root = $this->model->getRoot();
        $todo = array($root);

        $this->addNode($root, null);

        while(count($todo) > 0) {
            $parent = array_shift($todo);

            for($i = 0, $count = $this->model->getChildCount($parent); $i < $count; $i++) {
                $node   = $this->model->getChild($parent, $i);
                $todo[] = $node;

                $this->addNode($node, $parent);
            }
        }

        return $this;
    }

    /**
     * This method adds a node to the tree view, and placed it under its given parent node.
     *
     * @param object $node the node to insert
     * @param object $parent the parent under which the node has to be inserted
     * @return \Woody\Components\Controls\TreeView $this
     */
    protected function addNode($node, $parent) {
        $value = $this->nodeRenderer->__invoke($node);

        if($value !== null) {
            if(isset($this->controlNodes[self::getHash($parent)]))
                $parentID = $this->controlNodes[self::getHash($parent)];
            else
                $parentID = 0;

            $data = array (
                        array (
                            $value,
                            self::getHash($node),
                            $parentID,
                            0,
                            0,
                            2
                        )
                    );

            $this->controlNodes[self::getHash($node)] = wb_create_items($this->controlID, $data);
        }

        return $this;
    }

    /**
     * This method returns the node associated with the given node hash.
     *
     * @param int $nodeHash the hash by which to search for the node
     * @return object the node associated with the nodeHash, or null if no matching one could be found
     */
    final protected function findNodeByHash($nodeHash) {
        $root = $this->model->getRoot();
        $todo = array($root);

        while(count($todo) > 0) {
            $currentNode = array_shift($todo);

            if($nodeHash == self::getHash($currentNode)) {
                return $currentNode;
            } else {
                for($i = 0, $count = $this->model->getChildCount($currentNode); $i < $count; $i++) {
                    $todo[] = $this->model->getChild($currentNode, $i);
                }
            }
        }

        return null;
    }

    /**
     * This method calculates the hash for a given node in the tree view.
     *
     * @param object $node the tree node to get the hash for
     * @return int the hash for the tree node
     */
    private static function getHash($node) {
        return spl_object_hash((object)$node);
    }
}