<?php

// $Id: decorator_uri_test.php 162858 2004-07-08 10:18:48Z quipo $

require_once('simple_include.php');
require_once('calendar_include.php');

require_once('./decorator_test.php');

class TestOfDecoratorUri extends TestOfDecorator
{
    public function __construct()
    {
        $this->UnitTestCase('Test of Calendar_Decorator_Uri');
    }
    public function testFragments()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $this->assertEqual('year=&amp;month=&amp;day=&amp;hour=&amp;minute=&amp;second=', $Uri->this('second'));
    }
    public function testScalarFragments()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->setScalar();
        $this->assertEqual('&amp;&amp;&amp;&amp;&amp;', $Uri->this('second'));
    }
    public function testSetSeperator()
    {
        $Uri = new Calendar_Decorator_Uri($this->mockcal);
        $Uri->setFragments('year', 'month', 'day', 'hour', 'minute', 'second');
        $Uri->setSeparator('/');
        $this->assertEqual('year=/month=/day=/hour=/minute=/second=', $Uri->this('second'));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfDecoratorUri();
    $test->run(new HtmlReporter());
}
