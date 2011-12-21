<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class TreeView extends Control implements SplObserver {
    /**
     * the model of the tree view
     *
     * @var TreeModel
     */
    protected $model        = null;

    /**
     * the mapping of the tree view, used to customize the output of each tree view node
     *
     * @var Closure
     */
    protected $mapping      = null;

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
    public function __construct($id, $xPos, $yPos, $width, $height, $style = Control::DEFAULT_STYLE, $param = 0, $tabIndex = 0) {
        parent::__construct(TreeView, $id, '', $xPos, $yPos, $width, $height, $style, $param, $tabIndex);$this->mapping = self::getDefaultMapping();
    }

    public static function getDefaultMapping() {
        $callback = function($element) {
                        return $element;
                    };

        return $callback;
    }

    protected function findNode($nodeHash) {
        $root   = $this->model->getRoot();
        $todo[] = $root;

        while(count($todo) > 0) {
            $currentNode = array_shift($todo);

            if($nodeHash == $currentNode->getHash()) {
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
    public function getValue() {
        return $this->findNode(wb_get_value($this->controlID));
    }

    public function setValue($newValue) {
        wb_set_selected($this->controlID, $this->controlNodes[$newValue->getHash()]);

        return $this;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel(ITreeModel $model) {
        $this->model = $model;

        return $this;
    }

    public function getMapping() {
        return $this->mapping;
    }

    public function setMapping($mapping) {
        $this->mapping = $mapping;

        return $this;
    }

    public function update(SplSubject $treeModel) {
        $selectedItem = $this->getValue();

        $this->clear();

        $root	= $this->model->getRoot();
        $todo[] = $root;

        $this->addNode($root, null);

        while(count($todo) > 0) {
            $currentNode = array_shift($todo);

            for($i = 0, $count = $this->model->getChildCount($currentNode); $i < $count; $i++) {
                $todo[] = ($node = $this->model->getChild($currentNode, $i));

                $this->addNode($node, $this->controlNodes[$currentNode->getHash()]);
            }
        }

        if($selectedItem !== null) {
            $this->setValue($selectedItem);
        }

        return $this;
    }

    protected function addNode($newNode, $parentControlID) {
        $value = $this->mapping->__invoke($newNode);

        if($value !== null) {
            $data = array (
                        array (
                            $value,
                            $newNode->getHash(),
                            $parentControlID,
                            0,
                            0,
                            2
                        )
                    );

            $this->controlNodes[$newNode->getHash()] = wb_create_items($this->controlID, $data);
        }

        return $this;
    }

    public function expandNode($value) {
        if(isset($this->controlNodes[$value->getHash()])) {
            $currentNodeID = $this->controlNodes[$value->getHash()];
            while($currentNodeID != 0) {
                wb_set_state($this->controlID, $currentNodeID, TRUE);
                $currentNodeID = wb_get_parent($this->controlID, $currentNodeID);
            }
        }

        return $this;
    }

    public function collapseNode($value) {
        if(isset($this->controlNodes[$value->getHash()])) {
            wb_set_state($this->controlID, $this->controlNodes[$value->getHash()], FALSE);
        }

        return $this;
    }

    protected function clear() {
        wb_delete_items($this->controlID, null);
    }
}