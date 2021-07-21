<?php

namespace Measure;

use function is_int;
use function microtime;

class Measure
{
    /** @var float|int */
    private static $startTime;

    /** @var float|int */
    private static $stopTime;

    public static function start()
    {
        self::$startTime = self::getTime();
    }

    public static function stop()
    {
        self::$stopTime = self::getTime();
    }

    /**
     * @return float
     */
    public static function getResult()
    {
        $result = self::$stopTime - self::$startTime;

        if (is_int($result)) {
            // Convert nanoseconds to seconds
            $result /= 1e9;
        }

        return $result;
    }

    private static function getTime()
    {
        if (function_exists('hrtime')) {
            return \hrtime(true);
        }

        return microtime(true);
    }
}
