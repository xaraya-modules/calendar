<?php

// $Id: peardate_engine_test.php 269074 2008-11-15 21:21:42Z quipo $

require_once('simple_include.php');
require_once('calendar_include.php');

class TestOfPearDateEngine extends UnitTestCase
{
    public $engine;
    public function __construct()
    {
        $this->UnitTestCase('Test of Calendar_Engine_PearDate');
    }
    public function setUp()
    {
        $this->engine = new Calendar_Engine_PearDate();
    }
    public function testGetSecondsInMinute()
    {
        $this->assertEqual($this->engine->getSecondsInMinute(), 60);
    }
    public function testGetMinutesInHour()
    {
        $this->assertEqual($this->engine->getMinutesInHour(), 60);
    }
    public function testGetHoursInDay()
    {
        $this->assertEqual($this->engine->getHoursInDay(), 24);
    }
    public function testGetFirstDayOfWeek()
    {
        $this->assertEqual($this->engine->getFirstDayOfWeek(), 1);
    }
    public function testGetWeekDays()
    {
        $this->assertEqual($this->engine->getWeekDays(), [0,1,2,3,4,5,6]);
    }
    public function testGetDaysInWeek()
    {
        $this->assertEqual($this->engine->getDaysInWeek(), 7);
    }
    public function testGetWeekNInYear()
    {
        $this->assertEqual($this->engine->getWeekNInYear(2003, 11, 3), 45);
    }
    public function testGetWeekNInMonth()
    {
        $this->assertEqual($this->engine->getWeekNInMonth(2003, 11, 3), 2);
    }
    public function testGetWeeksInMonth0()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 11, 0), 6); //week starts on sunday
    }
    public function testGetWeeksInMonth1()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 11, 1), 5); //week starts on monday
    }
    public function testGetWeeksInMonth2()
    {
        $this->assertEqual($this->engine->getWeeksInMonth(2003, 2, 6), 4); //week starts on saturday
    }
    public function testGetWeeksInMonth3()
    {
        // Unusual cases that can cause fails (shows up with example 21.php)
        $this->assertEqual($this->engine->getWeeksInMonth(2004, 2, 1), 5);
        $this->assertEqual($this->engine->getWeeksInMonth(2004, 8, 1), 6);
    }
    public function testGetDayOfWeek()
    {
        $this->assertEqual($this->engine->getDayOfWeek(2003, 11, 18), 2);
    }
    public function testGetFirstDayInMonth()
    {
        $this->assertEqual($this->engine->getFirstDayInMonth(2003, 10), 3);
    }
    public function testGetDaysInMonth()
    {
        $this->assertEqual($this->engine->getDaysInMonth(2003, 10), 31);
    }
    public function testGetMinYears()
    {
        $this->assertEqual($this->engine->getMinYears(), 0);
    }
    public function testGetMaxYears()
    {
        $this->assertEqual($this->engine->getMaxYears(), 9999);
    }
    public function testDateToStamp()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->dateToStamp(2003, 10, 15, 13, 30, 45), $stamp);
    }
    public function testStampToSecond()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToSecond($stamp), 45);
    }
    public function testStampToMinute()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToMinute($stamp), 30);
    }
    public function testStampToHour()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToHour($stamp), 13);
    }
    public function testStampToDay()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToDay($stamp), 15);
    }
    public function testStampToMonth()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToMonth($stamp), 10);
    }
    public function testStampToYear()
    {
        $stamp = '2003-10-15 13:30:45';
        $this->assertEqual($this->engine->stampToYear($stamp), 2003);
    }
    public function testAdjustDate()
    {
        $stamp = '2004-01-01 13:30:45';
        $y = $this->engine->stampToYear($stamp);
        $m = $this->engine->stampToMonth($stamp);
        $d = $this->engine->stampToDay($stamp);

        //the first day of the month should be thursday
        $this->assertEqual($this->engine->getDayOfWeek($y, $m, $d), 4);

        $m--; // 2004-00-01 => 2003-12-01
        $this->engine->adjustDate($y, $m, $d, $dummy, $dummy, $dummy);

        $this->assertEqual($y, 2003);
        $this->assertEqual($m, 12);
        $this->assertEqual($d, 1);

        // get last day and check if it's wednesday
        $d = $this->engine->getDaysInMonth($y, $m);

        $this->assertEqual($this->engine->getDayOfWeek($y, $m, $d), 3);
    }
    public function testIsToday()
    {
        $stamp = date('Y-m-d H:i:s');
        $this->assertTrue($this->engine->isToday($stamp));
        $stamp = date('Y-m-d H:i:s', time() + 1000000000);
        $this->assertFalse($this->engine->isToday($stamp));
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = new TestOfPearDateEngine();
    $test->run(new HtmlReporter());
}
