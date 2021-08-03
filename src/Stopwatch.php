<?php

namespace Stopwatch;

class Stopwatch
{
    /** @var float|int */
    private static $startTime;

    /** @var float|int */
    private static $stopTime;

    private static $splits = array();

    public static function start()
    {
        self::$startTime = self::getTime();
    }

    /**
     * @param string $key A name to identify the intermediate time amongst the results
     *
     * @throws \InvalidArgumentException If an empty value or a non-string value was passed as key
     */
    public static function split($key)
    {
        if (!isset(self::$startTime)) {
            throw new \RuntimeException('The intermediate time cannot be recorded as the stopwatch has not been started.');
        }

        if (empty($key) || !is_string($key)) {
            throw new \InvalidArgumentException('Please enter a valid string as key to identify the intermediate time.');
        }

        self::$splits[$key] = self::getTime();
    }

    public static function stop()
    {
        self::$stopTime = self::getTime();
    }

    /**
     * @return float Either the amount of time passed since the stopwatch was started or the time elapsed between
     *               starting and stopping the stopwatch
     */
    public static function elapsed()
    {
        if (!isset(self::$startTime)) {
            throw new \RuntimeException('The elapsed time can not be reported as the stopwatch has never been started.');
        }

        if (isset(self::$stopTime)) {
            $currentTime = self::$stopTime;
        } else {
            $currentTime = self::getTime();
        }

        return self::diff(self::$startTime, $currentTime);
    }

    /**
     * @return array An array containing all the recorded intermediate times and the time difference between them
     */
    public static function getSplits()
    {
        // Stop the time if it was left running
        if (!isset(self::$stopTime)) {
            self::stop();
        }

        $result        = array();
        $previousSplit = 0;

        foreach (self::$splits as $key => $split) {
            $splitResult['sinceStart'] = self::diff(self::$startTime, $split);

            if ($previousSplit !== 0) {
                $splitResult['sincePreviousSplit'] = self::diff($previousSplit, $split);
            }

            $result[$key]  = $splitResult;
            $previousSplit = $split;
        }

        $result['overall'] = array(
            'sinceStart' => self::diff(self::$startTime, self::$stopTime)
        );

        return $result;
    }

    private static function getTime()
    {
        if (function_exists('hrtime')) {
            return \hrtime(true);
        }

        return microtime(true);
    }

    private static function diff($firstTime, $secondTime)
    {
        $result = $secondTime - $firstTime;

        if (is_int($result)) {
            // Convert nanoseconds to seconds
            $result /= 1e9;
        }

        return $result;
    }
}
