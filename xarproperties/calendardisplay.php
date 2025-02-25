<?php
/**
 * Calendar Module
 *
 * @package modules
 * @subpackage calendar module
 * @category Third Party Xaraya Module
 * @version 1.0.0
 * @copyright (C) copyright-placeholder
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */

/* Include files needed */
sys::import('modules.dynamicdata.class.properties');
sys::import('xaraya.structures.query');

class CalendarDisplayProperty extends DataProperty
{
    public $id         = 30081;
    public $name       = 'calendardisplay';
    public $desc       = 'Calendar Display';
    public $reqmodules = ['calendar'];

    public $timeframe  = 'week';
    public $owner;

    public function __construct(ObjectDescriptor $descriptor)
    {
        parent::__construct($descriptor);

        // Set for runtime
        $this->tplmodule = 'calendar';
        $this->filepath   = 'modules/calendar/xarproperties';
        $this->owner = $this->user()->getId();
    }

    public function showInput(array $data = [])
    {
        if (empty($data['role_id'])) {
            $data['role_id'] = $this->owner;
        }
        if (empty($data['timeframe'])) {
            $data['timeframe'] = $this->timeframe;
        }
        $this->template = 'calendardisplay_' . $data['timeframe'];
        $this->includes($data['timeframe']);

        $data = array_merge($data, $this->setup($data['timeframe'], $data['role_id']));
        return parent::showInput($data);
    }

    public function includes($timeframe)
    {
        switch ($timeframe) {
            case 'week':
                include_once(CALENDAR_ROOT . 'Week.php');
                sys::import("modules.calendar.class.Calendar.Decorator.event");
                sys::import("modules.calendar.class.Calendar.Decorator.weekevent");
                break;
            case 'month':
                include_once(CALENDAR_ROOT . 'Month/Weekdays.php');
                include_once(CALENDAR_ROOT . 'Day.php');
                sys::import("modules.calendar.class.Calendar.Decorator.event");
                sys::import("modules.calendar.class.Calendar.Decorator.monthevent");
                break;
            case 'year':
                include_once(CALENDAR_ROOT . 'Year.php');
                sys::import("modules.calendar.class.Calendar.Decorator.event");
                sys::import("modules.calendar.class.Calendar.Decorator.monthevent");
                sys::import("modules.calendar.class.Calendar.Decorator.yearevent");
                break;
        }
        sys::import("modules.calendar.class.Calendar.Decorator.Xaraya");
    }

    public function setup($timeframe, $role_id)
    {
        $data = $this->mod()->apiMethod('calendar', 'user', 'getUserDateTimeInfo');
        switch ($timeframe) {
            case 'week':
                $WeekEvents = new \Calendar_Week($data['cal_year'], $data['cal_month'], $data['cal_day'], $this->mod()->getVar('cal_sdow'));
                $start_time = $WeekEvents->thisWeek;
                $end_time = $WeekEvents->nextWeek;

                $events = $this->getEvents($start_time, $end_time, $role_id);

                $WeekDecorator = new \WeekEvent_Decorator($WeekEvents);
                $WeekDecorator->build($events);
                $data['Week'] = & $WeekDecorator;
                $data['cal_sdow'] = $this->mod()->getVar('cal_sdow');
                break;
            case 'month':
                $MonthEvents = new \Calendar_Month_Weekdays(
                    $data['cal_year'],
                    $data['cal_month'] + 1,
                    $this->mod()->getVar('cal_sdow')
                );
                $end_time = $MonthEvents->getTimestamp();
                $MonthEvents = new \Calendar_Month_Weekdays(
                    $data['cal_year'],
                    $data['cal_month'],
                    $this->mod()->getVar('cal_sdow')
                );
                $start_time = $MonthEvents->getTimestamp();

                $events = $this->getEvents($start_time, $end_time, $role_id);

                $MonthDecorator = new \MonthEvent_Decorator($MonthEvents);
                $MonthDecorator->build($events);
                $data['Month'] = & $MonthDecorator;
                break;
            case 'year':
                $Year = new \Calendar_Year($data['cal_year'] + 1);
                $end_time = $Year->getTimestamp();
                $Year = new \Calendar_Year($data['cal_year']);
                $start_time = $Year->getTimestamp();

                $events = $this->getEvents($start_time, $end_time, $role_id);

                $YearDecorator = new \YearEvent_Decorator($Year);
                $YearDecorator->build($events);
                $data['Year'] = & $YearDecorator->calendar;
                $data['cal_sdow'] = $this->mod()->getVar('cal_sdow');
                break;
        }
        return $data;
    }

    public function getEvents($start_time, $end_time, $role_id)
    {
        // get all the events. need to improve this query and combine it with the query in the template
        $xartable = & $this->db()->getTables();
        $q = new Query('SELECT', $xartable['calendar_event']);
        $q->ge('start_time', $start_time);
        $q->lt('start_time', $end_time);
        $q->eq('role_id', $role_id);
        //        $q->qecho();
        if (!$q->run()) {
            return;
        }
        return $q->output();
    }
}
