<?php

namespace Laravel\Forge\Traits;

trait LazyArrayAccess
{
    /**
     * Determines if given offset exists in current collection.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        $this->checkLazyLoad();

        return isset($this->items[$offset]);
    }

    /**
     * Puts value to given offset or appends it to current collection.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->checkLazyLoad();

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }

        $this->keys = array_keys($this->items);
    }

    /**
     * Retrieves given offset from current collection.
     * Returns NULL if no value found.
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $this->checkLazyLoad();

        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Drops given offset from current collection.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->checkLazyLoad();

        unset($this->items[$offset]);

        $this->keys = array_keys($this->items);
    }
}
