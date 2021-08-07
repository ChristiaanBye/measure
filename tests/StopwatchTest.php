<?php

namespace Stopwatch;

/** @runTestsInSeparateProcesses */
class StopwatchTest extends \PHPUnit_Framework_TestCase
{
    const TIME_BETWEEN_SPLITS = 200000;
    const DELTA = 0.05;

    public function testSimpleStartAndStop()
    {
        Stopwatch::start();

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        $elapsed = Stopwatch::elapsed();
        self::assertEquals(0.2, $elapsed, '', self::DELTA);

        Stopwatch::stop();

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the second time registration. This one should be greater than the previous one
        $elapsedAfterStopping1 = Stopwatch::elapsed();
        self::assertEquals(0.23, $elapsedAfterStopping1, '', self::DELTA);

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make a third time registration. This one should be the same as the second one as the stopwatch has been stopped
        $elapsedAfterStopping2 = Stopwatch::elapsed();
        self::assertSame($elapsedAfterStopping1, $elapsedAfterStopping2);
    }

    public function testWithSplits()
    {
        Stopwatch::start();

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        Stopwatch::split('1st');

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        Stopwatch::split('2nd');

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        Stopwatch::split('3rd');

        usleep(self::TIME_BETWEEN_SPLITS);

        // Stop the time and take a look at the results
        Stopwatch::stop();

        self::assertEquals(
            array(
                '1st' => array(
                    'sinceStart' => 0.2
                ),
                '2nd' => array(
                    'sinceStart' => 0.4,
                    'sincePreviousSplit' => 0.2
                ),
                '3rd' => array(
                    'sinceStart' => 0.6,
                    'sincePreviousSplit' => 0.2
                ),
                'overall' => array(
                    'sinceStart' => 0.8,
                )
            ),
            Stopwatch::getSplits(),
            '',
            self::DELTA
        );
    }

    public function testWithSplitsWithoutStoppingStopwatch()
    {
        Stopwatch::start();

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        Stopwatch::split('1st');

        usleep(self::TIME_BETWEEN_SPLITS);

        // Make the first time registration
        Stopwatch::split('2nd');

        usleep(self::TIME_BETWEEN_SPLITS);

        // Obtain the results without stopping
        $firstResults = Stopwatch::getSplits();

        usleep(self::TIME_BETWEEN_SPLITS);

        // Obtain the results again
        $secondResults = Stopwatch::getSplits();

        // The stopwatch has been left running in between, so these results must differ
        self::assertNotSame($firstResults, $secondResults);

        self::assertEquals(
            array(
                '1st' => array(
                    'sinceStart' => 0.2
                ),
                '2nd' => array(
                    'sinceStart' => 0.4,
                    'sincePreviousSplit' => 0.2
                ),
                'overall' => array(
                    'sinceStart' => 0.6,
                )
            ),
            $firstResults,
            '',
            self::DELTA
        );

        self::assertEquals(
            array(
                '1st' => array(
                    'sinceStart' => 0.2
                ),
                '2nd' => array(
                    'sinceStart' => 0.4,
                    'sincePreviousSplit' => 0.2
                ),
                'overall' => array(
                    'sinceStart' => 0.8,
                )
            ),
            $secondResults,
            '',
            self::DELTA
        );
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

    public function test_split_throwsInvalidArgumentException_ifNameAlreadyExists()
    {
        Stopwatch::start();
        Stopwatch::split('1st');

        $this->setExpectedException('InvalidArgumentException', 'The entered key "1st" is already in use.');
        Stopwatch::split('1st');
    }
}
