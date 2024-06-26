<?php

// $Id: validator_tests.php 159563 2004-05-24 22:25:43Z quipo $

require_once('simple_include.php');
require_once('calendar_include.php');

class ValidatorTests extends GroupTest
{
    public function __construct()
    {
        $this->GroupTest('Validator Tests');
        $this->addTestFile('validator_unit_test.php');
        $this->addTestFile('validator_error_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new ValidatorTests();
    $test->run(new HtmlReporter());
}
