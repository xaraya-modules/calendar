<?php

// $Id: hour_test.php 159563 2004-05-24 22:25:43Z quipo $

require_once('simple_include.php');
require_once('calendar_include.php');

require_once('./calendar_test.php');

class TestOfHour extends TestOfCalendar
{
    public function __construct()
    {
        $this->UnitTestCase('Test of Hour');
    }
    public function setUp()
    {
        $this->cal = new Calendar_Hour(2003, 10, 25, 13);
    }
    public function testPrevDay_Array()
    {
        $this->assertEqual(
            [
                'year'   => 2003,
                'month'  => 10,
                'day'    => 24,
                'hour'   => 0,
                'minute' => 0,
                'second' => 0, ],
            $this->cal->prevDay('array')
        );
    }
    public function testPrevMinute()
    {
        $this->assertEqual(59, $this->cal->prevMinute());
    }
    public function testThisMinute()
    {
        $this->assertEqual(0, $this->cal->thisMinute());
    }
    public function testNextMinute()
    {
        $this->assertEqual(1, $this->cal->nextMinute());
    }
    public function testPrevSecond()
    {
        $this->assertEqual(59, $this->cal->prevSecond());
    }
    public function testThisSecond()
    {
        $this->assertEqual(0, $this->cal->thisSecond());
    }
    public function testNextSecond()
    {
        $this->assertEqual(1, $this->cal->nextSecond());
    }
    public function testGetTimeStamp()
    {
        $stamp = mktime(13, 0, 0, 10, 25, 2003);
        $this->assertEqual($stamp, $this->cal->getTimeStamp());
    }
}

class TestOfHourBuild extends TestOfHour
{
    public function __construct()
    {
        $this->UnitTestCase('Test of Hour::build()');
    }
    public function testSize()
    {
        $this->cal->build();
        $this->assertEqual(60, $this->cal->size());
    }
    public function testFetch()
    {
        $this->cal->build();
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            $i++;
        }
        $this->assertEqual(60, $i);
    }
    public function testFetchAll()
    {
        $this->cal->build();
        $children = [];
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            $children[$i] = $Child;
            $i++;
        }
        $this->assertEqual($children, $this->cal->fetchAll());
    }
    public function testSelection()
    {
        require_once(CALENDAR_ROOT . 'Minute.php');
        $selection = [new Calendar_Minute(2003, 10, 25, 13, 32)];
        $this->cal->build($selection);
        $i = 0;
        while ($Child = $this->cal->fetch()) {
            if ($i == 32) {
                break;
            }
            $i++;
        }
        $this->assertTrue($Child->isSelected());
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfHour();
    $test->run(new HtmlReporter());
    $test = new TestOfHourBuild();
    $test->run(new HtmlReporter());
}
