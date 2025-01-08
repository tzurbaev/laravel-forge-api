<?php

namespace Laravel\Forge\Traits;

trait LazyIterator
{
    /**
     * Current iterator item.
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        $this->checkLazyLoad();

        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]) ? $this->items[$currentKey] : null;
    }

    /**
     * Current iterator position.
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        $this->checkLazyLoad();

        return $this->keys[$this->position];
    }

    /**
     * Increments iterator position.
     */
    public function next(): void
    {
        $this->checkLazyLoad();

        $this->position++;
    }

    /**
     * Rewinds iterator back to first position.
     */
    public function rewind(): void
    {
        $this->checkLazyLoad();

        $this->position = 0;
    }

    /**
     * Determines if there is some value at current iterator position.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->checkLazyLoad();

        if (!isset($this->keys[$this->position])) {
            return false;
        }

        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]);
    }
}
