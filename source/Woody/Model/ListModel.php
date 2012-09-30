<?php

namespace ws\loewe\Woody\Model;

class ListModel implements \SplSubject {
  /**
   * the data of the model
   *
   * @var \ArrayObject
   */
  protected $data = null;

  /**
   * the set of observers
   *
   * @var \SplObjectStorage
   */
  protected $observers = null;

  /**
   * the flag that determines if update() should be fired on the observers
   *
   * @var boolean
   */
  protected $isAdjusting = FALSE;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Traversable $data the data of the model
   */
  public function __construct(\Traversable $data = null) {
    $this->observers = new \SplObjectStorage();

    $this->setData($data);
  }

  /**
   * This method exchanges the data in the current model with the one given.
   *
   * @param \Traversable $data the new data of the model
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function setData(\Traversable $data = null) {
    $this->data = new \ArrayObject();

    // make sure the collection is indexed linearly
    if($data != null) {
      foreach($data as $dataItem) {
        $this->data[] = $dataItem;
      }
    }

    $this->notify();

    return $this;
  }

  /**
   * This method adds an item as the last element of the model.
   *
   * @param mixed $item the element to add
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function addElement($item) {
    // $this->data[] = $item; did not work when adding elements after having
    // deleted some, as appending was done at previous max index

    $this->data[$this->count()] = $item;

    $this->notify();

    return $this;
  }

  /**
   * This method adds multiple items as the last elements of the model.
   *
   * @param \Traversable $items the collection of elements to add
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function addElements(\Traversable $items) {
    $this->isAdjusting = TRUE;

    foreach($items as $item) {
      $this->addElement($item);
    }

    $this->isAdjusting = FALSE;

    $this->notify();

    return $this;
  }

  /**
   * This method returns the element at the specified index.
   *
   * @param int $index the index from where to get the element
   * @return mixed the element at the specified index, or null if none exists
   * at that index
   */
  public function getElementAt($index) {
    if($this->data->offsetExists($index)) {
      return $this->data[$index];
    }
    else {
      return null;
    }
  }

  /**
   * This method returns the index-position of the specified object in the model.
   *
   * @param mixed $element the element for which to get the index
   * @return int the index of the element
   */
  public function getIndexOf($element) {
    $currentIndex = 0;

    foreach($this->data as $currentElement) {
      if($currentElement == $element) {
        return $currentIndex;
      }
      ++$currentIndex;
    }

    return -1;
  }

  /**
   * This method returns the length of the model.
   *
   * @return int the size of the model
   */
  public function count() {
    return $this->data->count();
  }

  /**
   * This method adds an item at a specific index.
   *
   * @param mixed $element the element to add
   * @param int $index the index where to add the element
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function insertElementAt($element, $index) {
    // the index of the new element may not exeed the index after the currenly last element
    $index = min($index, $this->count());

    // starting from end, move every element positioned after the index where to insert up by one position ...
    for($i = $this->count() - 1; $i >= $index; --$i) {
      $this->data[$i + 1] = $this->data[$i];
    }

    // .. and add the new element at the freed slot
    $this->data[$index] = $element;

    $this->notify();

    return $this;
  }

  /**
   * This method clears the model of all elements.
   *
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function removeAllElements() {
    foreach($this->data as $entry) {
      unset($entry);
    }

    unset($this->data);

    $this->data = new \ArrayObject();

    $this->notify();

    return $this;
  }

  /**
   * This method removed the given element from the model.
   *
   * @param mixed $element the element to remove
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function removeElement($element) {
    $this->removeElementAt($this->getIndexOf($element));

    return $this;
  }

  /**
   * This method removes the item at a specific index.
   *
   * @param int $index the index of the item to remove
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function removeElementAt($index) {
    $count = $this->count();

    if($count === 0) {
      return $this;
    }

    if($index > -1) {
      // move every element after the element to be deleted up by one
      for($i = $index; $i < $count - 1; ++$i) {
        $this->data[$i] = $this->data[$i + 1];
      }

      // ... and finally, delete the last element
      unset($this->data[$this->count() - 1]);

      $this->notify();
    }

    return $this;
  }

  /**
   * This method adds an observer to the model.
   *
   * @param \SplObserver $observer the observer to add
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function attach(\SplObserver $observer) {
    $this->observers->attach($observer);

    return $this;
  }

  /**
   * This method removes an observer to the model.
   *
   * @param \SplObserver $observer the observer to remove
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function detach(\SplObserver $observer) {
    $this->observers->detach($observer);

    return $this;
  }

  /**
   * This method notifies all observer of the model to update themselves.
   *
   * @return \ws\loewe\Woody\Model\ListModel $this
   */
  public function notify() {
    if(!$this->isAdjusting) {
      foreach($this->observers as $observer) {
        $observer->update($this);
      }
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