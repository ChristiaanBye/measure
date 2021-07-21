<?php

use Measure\Measure;

require_once 'vendor/autoload.php';

Measure::start();
usleep(1000);
Measure::stop();

var_dump(Measure::getResult());
