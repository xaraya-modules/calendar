<?php
/**
* Description: same as 1.php, but using the PEAR::Date engine
* Notice the use of the CALENDAR_ENGINE constant, which
* switches the calculation "engine"
* Note: make sure PEAR::Date is a stable release!!!
*/
function getmicrotime()
{
    [$usec, $sec] = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

// Switch to PEAR::Date engine
define('CALENDAR_ENGINE', 'PearDate');

if (!@include 'Calendar/Calendar.php') {
    define('CALENDAR_ROOT', '../../');
}

if (!isset($_GET['y'])) {
    $_GET['y'] = 2003;
}
if (!isset($_GET['m'])) {
    $_GET['m'] = 8;
}
if (!isset($_GET['d'])) {
    $_GET['d'] = 9;
}
if (!isset($_GET['h'])) {
    $_GET['h'] = 12;
}
if (!isset($_GET['i'])) {
    $_GET['i'] = 34;
}
if (!isset($_GET['s'])) {
    $_GET['s'] = 46;
}

switch (@$_GET['view']) {
    default:
        $_GET['view'] = 'calendar_year';
        // no break
    case 'calendar_year':
        require_once CALENDAR_ROOT . 'Year.php';
        $c = new Calendar_Year($_GET['y']);
        break;
    case 'calendar_month':
        require_once CALENDAR_ROOT . 'Month.php';
        $c = new Calendar_Month($_GET['y'], $_GET['m']);
        break;
    case 'calendar_day':
        require_once CALENDAR_ROOT . 'Day.php';
        $c = new Calendar_Day($_GET['y'], $_GET['m'], $_GET['d']);
        break;
    case 'calendar_hour':
        require_once CALENDAR_ROOT . 'Hour.php';
        $c = new Calendar_Hour($_GET['y'], $_GET['m'], $_GET['d'], $_GET['h']);
        break;
    case 'calendar_minute':
        require_once CALENDAR_ROOT . 'Minute.php';
        $c = new Calendar_Minute($_GET['y'], $_GET['m'], $_GET['d'], $_GET['h'], $_GET['i']);
        break;
    case 'calendar_second':
        require_once CALENDAR_ROOT . 'Second.php';
        $c = new Calendar_Second($_GET['y'], $_GET['m'], $_GET['d'], $_GET['h'], $_GET['i'], $_GET['s']);
        break;
}

// Convert timestamp to human readable date
$date = new Date($c->getTimestamp());

echo('<h1>Using PEAR::Date engine</h1>');
echo('Viewing: ' . @$_GET['view'] . '<br />');
echo('The time is now: ' . $date->format('%Y %a %e %T') . '<br >');

$i = 1;
echo('<h1>First Iteration</h1>');
echo('<p>The first iteration is more "expensive", the calendar data
        structures having to be built.</p>');
$start = getmicrotime();
$c->build();
while ($e = $c->fetch()) {
    $class = strtolower(get_class($e));
    $link = "&y=" . $e->thisYear() . "&m=" . $e->thisMonth() . "&d=" . $e->thisDay() .
        "&h=" . $e->thisHour() . "&i=" . $e->thisMinute() . "&s=" . $e->thisSecond();
    $method = 'this' . str_replace('calendar_', '', $class);
    echo("<a href=\"" . $_SERVER['PHP_SELF'] . "?view=" . $class . $link . "\">" . $e->{$method}() . "</a> : ");
    if (($i % 10) == 0) {
        echo('<br>');
    }
    $i++;
}
echo('<p><b>Took: ' . (getmicrotime() - $start) . ' seconds</b></p>');

$i = 1;
echo('<h1>Second Iteration</h1>');
echo('<p>This second iteration is faster, the data structures
        being re-used</p>');
$start = getmicrotime();
while ($e = $c->fetch()) {
    $class = strtolower(get_class($e));
    $link = "&y=" . $e->thisYear() . "&m=" . $e->thisMonth() . "&d=" . $e->thisDay() .
        "&h=" . $e->thisHour() . "&i=" . $e->thisMinute() . "&s=" . $e->thisSecond();
    $method = 'this' . str_replace('calendar_', '', $class);
    echo("<a href=\"" . $_SERVER['PHP_SELF'] . "?view=" . $class . $link . "\">" . $e->{$method}() . "</a> : ");
    if (($i % 10) == 0) {
        echo('<br>');
    }
    $i++;
}
echo('<p><b>Took: ' . (getmicrotime() - $start) . ' seconds</b></p>');
