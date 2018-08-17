<?php

define('TEST_ROOT_PATH', __DIR__);

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../src'));

require_once __DIR__ . '/Spyc.php';

if (version_compare(phpversion(), '7.0.0', '<')) {
    class_alias('\\PHPUnit_Framework_TestCase', 'PHPUnit\\Framework\\TestCase');
}

date_default_timezone_set('UTC');
require __DIR__ . '/../vendor/autoload.php';
