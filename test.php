<?php

use Stopwatch\Stopwatch;

require_once 'vendor/autoload.php';

Stopwatch::start();
usleep(1000);
var_dump(Stopwatch::elapsed());
Stopwatch::split('Split 1');
var_dump(Stopwatch::elapsed());
usleep(1200);
Stopwatch::split('Split 2');
usleep(1400);
Stopwatch::stop();
var_dump(Stopwatch::elapsed());
var_dump(Stopwatch::elapsed());
var_dump(Stopwatch::elapsed());

var_dump(Stopwatch::getSplits());
