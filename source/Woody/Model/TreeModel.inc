<?php

namespace Woody\Model;

/**
 * This class acts as an abstract ITreeModel implementation, implementing the most basic elements.
 */
abstract class TreeModel implements ITreeModel
{
    /**
     * the root of the tree
     *
     * @var object
     */
    protected $root = null;

    /**
     * This method acts as the constructor of the TreeNodeTreeModel.
     *
     * @param object $root the root of the tree
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * This method returns the root of the tree.
     *
     * @return object the root of the tree
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * This method returns true if the given node is a leaf.
     *
     * @param object $node the node
     * @return boolean true, if the the node is a leaf, else false
     */
    public function isLeaf($node)
    {
        return $this->getChildCount($node) === 0;
    }

    /**
     * This method returns the label of the given node.
     *
     * This default implementation return the empty string.
     *
     * @param type $node the node for which to get the label
     * @return string the label of the given node
     */
    public function getLabel($node)
    {
        return '';
    }
}