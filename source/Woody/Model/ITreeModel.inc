<?php

namespace Woody\Model;

/**
 * This interface defines the basic methods that are neccessary for a data structure to be processed by the Reingold-Tilford tree drawing algorithm.
 */
interface ITreeModel
{
    /**
     * This method returns the child at the given index of the given parent.
     *
     * @param object $parent the parent to get the child from
     * @param int $index the index at where to search the child
     * @return object the child
     */
    public function getChild($parent, $index);

    /**
     * This method returns the number of children of the given node.
     *
     * @param object $parent the parent to get the child count from
     * @return boolean int the child count of the node
     */
    function getChildCount($parent);

    /**
     * This method returns the index of the given child of the given parent.
     *
     * @param object $parent the parent to search in
     * @param object $child the child whose index has to be determined
     * @return int the index of the child
     */
    function getIndexOfChild($parent, $child);

    /**
     * This method returns the root of the tree
     *
     * @return onject the root of the tree
     */
    function getRoot();

    /**
     * This method decides whether or not the given node is a leaf or not
     *
     * @param object $node the node to make the test for
     * @return boolean true, if the given node is a leaf, else false
     */
    function isLeaf($node);

    /**
     * This method returns the label associated with a tree node
     */
    function getLabel($node);
}
