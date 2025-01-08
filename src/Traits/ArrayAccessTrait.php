<?php

namespace Laravel\Forge\Traits;

trait ArrayAccessTrait
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
        return isset($this->data[$offset]);
    }

    /**
     * Puts value to given offset or appends it to current collection.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }

        $this->keys = array_keys($this->data);
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
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Drops given offset from current collection.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);

        $this->keys = array_keys($this->data);
    }
}
