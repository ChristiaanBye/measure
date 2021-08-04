Stopwatch
===
As the name implies, this is a helper for profiling code in PHP. The library is designed with the following in mind:

1. **Absolute minimal overhead**. The reason you are reading this, is you likely have one or more short tasks to profile. Polluting the results by adding a few hundreds of a second in overhead, won't do anybody good.
1. **Drop-dead simple API**. No decision-making in how the results should be presented or how to name the measuring instance. Just a simple start and stop with maybe a few laps in between. If you require more advanced features, in-code profiling might not be the most suitable approach for your use case. 
1. **Maximum compatibility with older PHP versions**. The most of us have had the joy of working with end-of-life PHP versions. Thankfully versioning exists, so one could install an older version of their library of choice. Unfortunately this sometimes does come at the expense of a subtly altered public API. I'd rather remove mental friction than add to it, so you can use the very same library with age-old PHP 5.6 all the way to modern-day PHP 8.

Prerequisites
---
* PHP 5.4 or later

Installation
---
Installation can be easily done using Composer:

```shell
composer require christiaanbye/stopwatch
```

Advanced users may advocate to add the `--dev` flag and rely on their quality gate to detect usages of the undefined Stopwatch class to prevent accidental production use.

Usage
---
For basic use, you can use the `start()`, `stop()` and `elapsed()` methods:

```php
use Stopwatch\Stopwatch;

Stopwatch::start(); // Start the stopwatch

// ... run your tasks here

echo Stopwatch::elapsed(); // Optionally take a peek at the elapsed time

// ... run some more tasks here

Stopwatch::stop(); // Stop the stopwatch once your tasks have ran

echo Stopwatch::elapsed(); // Output the time elapsed between the moment the stopwatch was started and stopped
```

The `elapsed()` method returns a float of one of the following:

* If the stopwatch is still running, the amount of time passed since starting the stopwatch and now
* If the stopwatch has been stopped, the amount of time passed since starting and stopping the stopwatch

The latter can be useful if you wish to log the elapsed time further down execution.

In more advanced use cases, one can also make use of the `split()` method in conjunction with the `getSplits()` method. This allows you to record the intermediate time between tasks:

```php
use Stopwatch\Stopwatch;

Stopwatch::start();

// ... run a first batch of tasks here

Stopwatch::split('1st batch of tasks');

// ... run a second batch of tasks here

Stopwatch::split('2nd batch of tasks');

// ... run a third batch of tasks here

Stopwatch::split('3rd batch of tasks');

// ... optionally run more tasks for which an intermediate time is not necessary

Stopwatch::stop();

print_r(Stopwatch::getSplits()); // Output the time elapsed between the moment the stopwatch was started and stopped
```

The `getSplits()` method returns all intermediates and the overall time as an array containing the following:

* The time since the stopwatch was started
* The time between the previous and current intermediate

The result of the above code looks as follows:

```
Array
(
    [1st batch of tasks] => Array
        (
            [sinceStart] => 0.007193305
        )

    [2nd batch of tasks] => Array
        (
            [sinceStart] => 0.012397519
            [sincePreviousSplit] => 0.005204214
        )

    [3rd batch of tasks] => Array
        (
            [sinceStart] => 0.01354921
            [sincePreviousSplit] => 0.001151691
        )

    [overall] => Array
        (
            [sinceStart] => 0.01888658
        )

)
```

Similarly to the `elapsed()` method, the stopwatch can be stopped prior to calling `getSplits()` so the recorded execution times are not affected by logging late during runtime.
