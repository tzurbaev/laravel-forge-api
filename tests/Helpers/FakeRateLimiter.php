<?php

namespace Laravel\Tests\Forge\Helpers;

class FakeRateLimiter
{

    /**
     * The fake rate limiter count
     *
     * @var int
     */
    protected $limiterCount = 0;

    /**
     * Simulate the function call to rate limiter
     *
     * In the wild, this function would determine whether a delay
     * was required; here we just increment a value to check later
     */
    public function limit()
    {
        $this->limiterCount++;
    }

    /**
     * Returns the limiterCount
     *
     * @return int
     */
    public function getLimiterCount()
    {
        return $this->limiterCount;
    }
}
