<?php
/**
* Description: a SOAP Calendar Server
*/
if (!@include('SOAP' . DIRECTORY_SEPARATOR . 'Server.php')) {
    die('You must have PEAR::SOAP installed');
}

if (!@include 'Calendar' . DIRECTORY_SEPARATOR . 'Calendar.php') {
    define('CALENDAR_ROOT', '../../');
}

class Calendar_Server
{
    public $__dispatch_map = [];
    public $__typedef      = [];

    public function __construct()
    {
        $this->__dispatch_map['getMonth'] =
            ['in'  => ['year' => 'int', 'month' => 'int'],
                  'out' => ['month' => '{urn:PEAR_SOAP_Calendar}Month'],
                  ];
        $this->__typedef['Month'] = [
                'monthname' => 'string',
                'days' => '{urn:PEAR_SOAP_Calendar}MonthDays',
            ];
        $this->__typedef['MonthDays'] = [['{urn:PEAR_SOAP_Calendar}Day']];
        $this->__typedef['Day'] = [
                'isFirst' => 'int',
                'isLast'  => 'int',
                'isEmpty' => 'int',
                'day'     => 'int', ];
    }

    public function __dispatch($methodname)
    {
        return $this->__dispatch_map[$methodname] ?? null;
    }

    public function getMonth($year, $month)
    {
        require_once(CALENDAR_ROOT . 'Month' . DIRECTORY_SEPARATOR . 'Weekdays.php');
        $Month = new Calendar_Month_Weekdays($year, $month);
        if (!$Month->isValid()) {
            $V = $Month->getValidator();
            $errorMsg = '';
            while ($error = $V->fetch()) {
                $errorMsg .= $error->toString() . "\n";
            }
            return new SOAP_Fault($errorMsg, 'Client');
        } else {
            $monthname = date('F Y', $Month->getTimeStamp());
            $days = [];
            $Month->build();
            while ($Day = $Month->fetch()) {
                $day = [
                    'isFirst' => (int) $Day->isFirst(),
                    'isLast'  => (int) $Day->isLast(),
                    'isEmpty' => (int) $Day->isEmpty(),
                    'day'     => (int) $Day->thisDay(),
                    ];
                $days[] = $day;
            }
            return ['monthname' => $monthname, 'days' => $days];
        }
    }
}

$server = new SOAP_Server();
$server->_auto_translation = true;
$calendar = new Calendar_Server();
$server->addObjectMap($calendar, 'urn:PEAR_SOAP_Calendar');

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    $server->service($GLOBALS['HTTP_RAW_POST_DATA']);
} else {
    require_once 'SOAP' . DIRECTORY_SEPARATOR . 'Disco.php';
    $disco = new SOAP_DISCO_Server($server, "PEAR_SOAP_Calendar");
    if (isset($_SERVER['QUERY_STRING']) &&
        strcasecmp($_SERVER['QUERY_STRING'], 'wsdl') == 0) {
        header("Content-type: text/xml");
        echo $disco->getWSDL();
    } else {
        echo 'This is a PEAR::SOAP Calendar Server. For client try <a href="8.php">here</a><br />';
        echo 'For WSDL try <a href="?wsdl">here</a>';
    }
    exit;
}
