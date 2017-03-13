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
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Puts value to given offset or appends it to current collection.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
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
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Drops given offset from current collection.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);

        $this->keys = array_keys($this->data);
    }
}
