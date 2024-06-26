<?php
/**
* Description: demonstrates a decorator used to "attach a payload" to a selection
* to make it available when iterating over calendar children
*/

//if you use ISO-8601 dates, switch to PearDate engine
define('CALENDAR_ENGINE', 'PearDate');

if (!@include 'Calendar/Calendar.php') {
    define('CALENDAR_ROOT', '../../');
}

require_once CALENDAR_ROOT . 'Month/Weekdays.php';
require_once CALENDAR_ROOT . 'Day.php';
require_once CALENDAR_ROOT . 'Decorator.php';

// accepts multiple entries
class DiaryEvent extends Calendar_Decorator
{
    public $entries = [];

    public function DiaryEvent($calendar)
    {
        parent::__construct($calendar);
    }

    public function addEntry($entry)
    {
        $this->entries[] = $entry;
    }

    public function getEntry()
    {
        $entry[1] = current($this->entries);
        $entry['value'] = current($this->entries);
        $entry[0] = key($this->entries);
        $entry['key'] = key($this->entries);
        next($this->entries);
        if ($entry) {
            return $entry['value'];
        } else {
            reset($this->entries);
            return false;
        }
    }
}

class MonthPayload_Decorator extends Calendar_Decorator
{
    //Calendar engine
    public $cE;
    public $tableHelper;

    public $year;
    public $month;
    public $firstDay = false;

    public function build($events = [])
    {
        require_once CALENDAR_ROOT . 'Day.php';
        require_once CALENDAR_ROOT . 'Table/Helper.php';

        $this->tableHelper = new Calendar_Table_Helper($this, $this->firstDay);
        $this->cE = $this->getEngine();
        $this->year  = $this->thisYear();
        $this->month = $this->thisMonth();

        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $Day = new Calendar_Day(2000, 1, 1); // Create Day with dummy values
            $Day->setTimeStamp($this->cE->dateToStamp($this->year, $this->month, $i));
            $this->children[$i] = new DiaryEvent($Day);
        }
        if (count($events) > 0) {
            $this->setSelection($events);
        }
        Calendar_Month_Weekdays::buildEmptyDaysBefore();
        Calendar_Month_Weekdays::shiftDays();
        Calendar_Month_Weekdays::buildEmptyDaysAfter();
        Calendar_Month_Weekdays::setWeekMarkers();
        return true;
    }

    public function setSelection($events)
    {
        $daysInMonth = $this->cE->getDaysInMonth($this->year, $this->month);
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $stamp1 = $this->cE->dateToStamp($this->year, $this->month, $i);
            $stamp2 = $this->cE->dateToStamp($this->year, $this->month, $i + 1);
            foreach ($events as $event) {
                if (($stamp1 >= $event['start'] && $stamp1 < $event['end']) ||
                    ($stamp2 >= $event['start'] && $stamp2 < $event['end']) ||
                    ($stamp1 <= $event['start'] && $stamp2 > $event['end'])
                ) {
                    $this->children[$i]->addEntry($event);
                    $this->children[$i]->setSelected();
                }
            }
        }
    }

    public function fetch()
    {
        $child[1] = current($this->children);
        $child['value'] = current($this->children);
        $child[0] = key($this->children);
        $child['key'] = key($this->children);
        next($this->children);
        if ($child) {
            return $child['value'];
        } else {
            reset($this->children);
            return false;
        }
    }
}

// Calendar instance used to get the dates in the preferred format:
// you can switch Calendar Engine and the example still works
$cal = new Calendar();

$events = [];
//add some events
$events[] = [
    'start' => $cal->cE->dateToStamp(2004, 6, 1, 10),
    'end'   => $cal->cE->dateToStamp(2004, 6, 1, 12),
    'desc'  => 'Important meeting',
];
$events[] = [
    'start' => $cal->cE->dateToStamp(2004, 6, 1, 21),
    'end'   => $cal->cE->dateToStamp(2004, 6, 1, 23, 59),
    'desc'  => 'Dinner with the boss',
];
$events[] = [
    'start' => $cal->cE->dateToStamp(2004, 6, 5),
    'end'   => $cal->cE->dateToStamp(2004, 6, 10, 23, 59),
    'desc'  => 'Holidays!',
];



$Month = new Calendar_Month_Weekdays(2004, 6);
$MonthDecorator = new MonthPayload_Decorator($Month);
$MonthDecorator->build($events);

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> Calendar </title>
<style text="text/css">
table {
    border-collapse: collapse;
}
caption {
    font-family: verdana;
    font-size: 14pt;
    padding-bottom: 4pt;
}
th {
    font-family: verdana;
    font-size: 11px;
    text-align: center;
    background-color: #e7e3e7;
    padding: 5pt;
    line-height: 150%;
    border: 1px solid #ccc;
}
td {
    font-family: verdana;
    font-size: 11px;
    text-align: left;
    vertical-align: top;
}
td.calCell {
    border: 1px solid #b5bece;
    padding: 3px;
}
td.calCellEmpty {
    background-color: #f3f3f7;
}
td.calCellBusy {
    background-color: #efeffa;
}
div.dayNumber {
    text-align: right;
    background-color: #f8f8f8;
    border-bottom: 1px solid #ccc;
}
ul {
    margin-left: 0;
    margin-top: 5pt;
    padding: 0 10pt 0 12pt;
    list-style-type: square;
}
</style>
</head>

<body>

<h2>Sample Calendar Payload Decorator (using <?php echo CALENDAR_ENGINE; ?> engine)</h2>
<table class="calendar" width="98%" cellspacing="0" cellpadding="0">
<caption>
    <?php echo $MonthDecorator->thisMonth() . ' / ' . $MonthDecorator->thisYear(); ?>
</caption>
<tr>
    <th>Monday</th>
    <th>Tuesday</th>
    <th>Wednesday</th>
    <th>Thursday</th>
    <th>Friday</th>
    <th>Saturday</th>
    <th>Sunday</th>
</tr>
<?php
while ($Day = $MonthDecorator->fetch()) {
    if ($Day->isFirst()) {
        echo "<tr>\n";
    }

    echo '<td class="calCell';
    if ($Day->isSelected()) {
        echo ' calCellBusy';
    } elseif ($Day->isEmpty()) {
        echo ' calCellEmpty';
    }
    echo '">';
    echo '<div class="dayNumber">' . $Day->thisDay() . '</div>';

    if ($Day->isEmpty()) {
        echo '&nbsp;';
    } else {
        echo '<div class="dayContents"><ul>';
        while ($entry = $Day->getEntry()) {
            echo  '<li>' . $entry['desc'] . '</li>';
            //you can print the time range as well
        }
        echo '</ul></div>';
    }
    echo '</td>';

    if ($Day->isLast()) {
        echo "</tr>\n";
    }
}
?>
</table>
</body>
</html>
