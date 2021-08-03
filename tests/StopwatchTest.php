<?php

namespace Stopwatch;

/** @runTestsInSeparateProcesses */
class StopwatchTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleStartAndStop()
    {
        Stopwatch::start();

        usleep(200000);

        // Make the first time registration
        $elapsed = Stopwatch::elapsed();
        self::assertEquals(0.2, $elapsed, '', 0.01);

        Stopwatch::stop();

        usleep(200000);

        // Make the second time registration. This one should be greater than the previous one
        $elapsedAfterStopping1 = Stopwatch::elapsed();
        self::assertEquals(0.23, $elapsedAfterStopping1, '', 0.01);

        usleep(200000);

        // Make a third time registration. This one should be the same as the second one as the stopwatch has been stopped
        $elapsedAfterStopping2 = Stopwatch::elapsed();
        self::assertSame($elapsedAfterStopping1, $elapsedAfterStopping2);
    }

    public function test_elapsed_throwsRuntimeException_ifStopwatchHasNotBeenStarted()
    {
        $this->setExpectedException('RuntimeException', 'The elapsed time can not be reported as the stopwatch has never been started.');
        Stopwatch::elapsed();
    }

    public function test_split_throwsRuntimeException_ifStopwatchHasNotBeenStarted()
    {
        $this->setExpectedException('RuntimeException', 'The intermediate time cannot be recorded as the stopwatch has not been started.');
        Stopwatch::split('Test');
    }

    public function test_split_throwsInvalidArgumentException_ifNameForSplitWasProvided()
    {
        Stopwatch::start(); // Start the stopwatch to prevent a different exception from being thrown

        $this->setExpectedException('InvalidArgumentException', 'Please enter a valid string as key to identify the intermediate time.');
        Stopwatch::split(null);
    }
}
