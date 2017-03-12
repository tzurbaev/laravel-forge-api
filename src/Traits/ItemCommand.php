<?php

namespace Laravel\Forge\Traits;

trait ItemCommand
{
    /**
     * Item ID.
     */
    protected $itemId;

    /**
     * Set Item ID.
     *
     * @param int|string $itemId
     *
     * @return static
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get Item ID.
     *
     * @return int|string|null
     */
    public function getItemId()
    {
        return $this->itemId;
    }
}
