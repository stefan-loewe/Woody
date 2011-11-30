<?php

namespace Woody\Model;

class ListModel implements \SplSubject, \IteratorAggregate
{
    /**
     * the data of the model
     *
     * @var \ArrayObject
     */
    protected $data         = null;

    /**
     * the set of observers
     *
     * @var \SplObjectStorage
     */
    protected $observers    = null;

    /**
     * the flag that determines if update() should be fired on the observers
     *
     * @var boolean
     */
    protected $isAdjusting  = FALSE;

    public function __construct(\Traversable $data = null)
    {
        $this->observers = new \SplObjectStorage();

        $this->setData($data);
    }

    public function setData(\Traversable $data = null)
    {
        $this->data = new \ArrayObject();

        // make sure the collection is indexed linearly
        if($data != null)
        {
            foreach($data as $dataItem)
                $this->data[] = $dataItem;
        }

        $this->notify();

        return $this;
    }

    // Adds an item at the end of the model.
    public function addElement($item)
    {
        // $this->data[] = $item; did not work when adding elements after having deleted some, as appending was done at previous max index

        $this->data[$this->count()] = $item;

        $this->notify();

        return $this;
    }

    // Adds an item at the end of the model.
    public function addElements(\ArrayAccess $items)
    {
        $this->isAdjusting = TRUE;

        foreach($items as $item)
            $this->addElement($item);

        $this->isAdjusting = FALSE;

        $this->notify();

        return $this;
    }

    // Returns the value at the specified index.
    public function getElementAt($index)
    {
        if($this->data->offsetExists($index))
            return $this->data[$index];

        else
            return null;
    }

    // Returns the index-position of the specified object in the list.
    public function getIndexOf($element)
    {
        $currentIndex = 0;

        foreach($this->data as $key => $currentElement)
        {
            if($currentElement == $element)
                return $currentIndex;

            ++$currentIndex;
        }

        return -1;
    }

    // Returns the length of the list.
    public function count()
    {
        return $this->data->count();
    }

    // Adds an item at a specific index.
    public function insertElementAt($element, $index)
    {
        // the index of the new element may not exeed the index after the currenly last element
        $index = min($index, $this->count());

        // starting from end, move every element positioned after the index where to insert up by one position ...
        for($i = $this->count() - 1; $i >= $index; --$i)
            $this->data[$i + 1] = $this->data[$i];

        // .. and add the new element at the freed slot
        $this->data[$index] = $element;

        $this->notify();

        return $this;
    }

    // Empties the list.
    public function removeAllElements()
    {
        foreach($this->data as $index => $entry)
            unset($entry);

        unset($this->data);

        $this->data = new \ArrayObject();

        $this->notify();

        return $this;
    }

    // Removes an item from the model.
    public function removeElement($element)
    {
        $this->removeElementAt($this->getIndexOf($element));

        return $this;
    }

    // Removes an item at a specific index.
    public function removeElementAt($index)
    {
        if($index > -1)
        {
            // starting from the element right after the index to be deleted, move every following element up by one position ...
            for($i = $index; $i < $this->count() - 1; ++$i)
                $this->data[$i] = $this->data[$i + 1];

            // ... and finally, delete the last element
            unset($this->data[$this->count() - 1]);

            $this->notify();
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->data->getIterator();
    }

    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);

        return $this;
    }

    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);

        return $this;
    }

    public function notify()
    {
        if(!$this->isAdjusting)
        {
            foreach($this->observers as $observer)
                $observer->update($this);
        }

        return $this;
    }
}
/*
class ObservingListModel extends ListModel implements SplObserver
{
    public function __construct(ArrayAccess $data)
    {
        parent::__construct($data);
    }

	public function update(SplSubject $subject)
	{
		$this->setData($subject);

        return $this;
	}
}
*/