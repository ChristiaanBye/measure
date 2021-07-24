<?php

use Measure\Measure;

require_once 'vendor/autoload.php';

Measure::start();
usleep(1000);
var_dump(Measure::elapsed());
Measure::split('Split 1');
var_dump(Measure::elapsed());
usleep(1200);
Measure::split('Split 2');
usleep(1400);
Measure::stop();
var_dump(Measure::elapsed());
var_dump(Measure::elapsed());
var_dump(Measure::elapsed());

var_dump(Measure::getSplits());
