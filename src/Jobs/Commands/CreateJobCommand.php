<?php

namespace Laravel\Forge\Jobs\Commands;

use InvalidArgumentException;

class CreateJobCommand extends JobCommand
{
    /**
     * Determines if command can run.
     *
     * @return bool
     */
    public function runnable()
    {
        if ($this->getPayloadData('frequency') !== 'custom') {
            return true;
        }

        // If we're using 'custom' frequency,
        // all listed fields are required.

        return $this->hasPayloadData(['minute', 'hour', 'day', 'month', 'weekday']);
    }

    /**
     * Set job command.
     *
     * @param string $command
     *
     * @return static
     */
    public function schedule(string $command)
    {
        return $this->attachPayload('command', $command);
    }

    /**
     * Set job user.
     *
     * @param string $user
     *
     * @return static
     */
    public function runAs(string $user)
    {
        return $this->attachPayload('user', $user);
    }

    /**
     * Indicates that job should run every minute.
     *
     * @return static
     */
    public function everyMinute()
    {
        return $this->attachPayload('frequency', 'minutely');
    }

    /**
     * Indicates that job should run every hour.
     *
     * @return static
     */
    public function hourly()
    {
        return $this->attachPayload('frequency', 'hourly');
    }

    /**
     * Indicates that job should run every day at midnight.
     *
     * @return static
     */
    public function nightly()
    {
        return $this->attachPayload('frequency', 'nightly');
    }

    /**
     * Indicates that job should run every week.
     *
     * @return static
     */
    public function weekly()
    {
        return $this->attachPayload('frequency', 'weekly');
    }

    /**
     * Indicates that job should run every month.
     *
     * @return static
     */
    public function monthly()
    {
        return $this->attachPayload('frequency', 'monthly');
    }

    /**
     * Schedule job at hour:minute.
     *
     * @param string $time
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public function atTime(string $time)
    {
        $exploded = explode(':', $time);

        if (sizeof($exploded) !== 2) {
            throw new InvalidArgumentException('Given argument "'.$time.'" is not a valid time.');
        }

        list($hour, $minute) = $exploded;

        return $this->attachPayload('frequency', 'custom')
            ->attachPayload('hour', $hour)
            ->attachPayload('minute', $minute);
    }

    /**
     * Schedule job at given day.
     *
     * @param string|int $day
     *
     * @return static
     */
    public function atDay($day)
    {
        return $this->attachPayload('frequency', 'custom')
            ->attachPayload('day', $day);
    }

    /**
     * Schedule job at given month.
     *
     * @param string|int $month
     *
     * @return static
     */
    public function atMonth($month)
    {
        return $this->attachPayload('frequency', 'custom')
            ->attachPayload('month', $month);
    }

    /**
     * Schedule job at given weekday.
     *
     * @param string|int $weekday
     *
     * @return static
     */
    public function atWeekday($weekday)
    {
        return $this->attachPayload('frequency', 'custom')
            ->attachPayload('weekday', $weekday);
    }
}
