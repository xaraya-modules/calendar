<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\UserGui;


use Xaraya\Modules\Calendar\UserGui;
use Xaraya\Modules\MethodClass;
use xarMod;
use xarModVars;
use xarDB;
use Query;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user day function
 * @extends MethodClass<UserGui>
 */
class DayMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    public function __invoke(array $args = [])
    {
        $data = xarMod::apiFunc('calendar', 'user', 'getUserDateTimeInfo');
        $DayEvents = new \Calendar_Day($data['cal_year'], $data['cal_month'], $data['cal_day']);
        $args = [
            'day' => &$DayEvents,
        ];
        $day_endts = $DayEvents->getTimestamp() + $this->mod()->getVar('day_end') + 3600;

        // get all the events. need to improve this query
        $xartable = & $this->db()->getTables();
        $q = new Query('SELECT', $xartable['calendar_event']);
        //        $q->qecho();
        if (!$q->run()) {
            return;
        }
        $events = $q->output();

        // Do some calculations to complete the entries' info
        $slots = [];

        // Loop through the events
        $eventcount = count($events);
        for ($j = 0;$j < $eventcount;$j++) {
            // make sure events don't go past the end of the day
            $events[$j]['end_time'] = min($events[$j]['end_time'], $day_endts);

            $placed = false;
            $slotcount = count($slots);
            for ($i = 0;$i < $slotcount;$i++) {
                if ($events[$j]['start_time'] >= $slots[$i][1]) {
                    foreach ($slots as $slot) {
                        $events[$slot[0]]['neighbors'] = $slotcount;
                    }
                    $thisslot = $i;
                    $slots = [0 => [$j,$events[$j]['end_time']]];
                    $placed = true;
                    break;
                }
            }
            if (!$placed) {
                $thisslot = $slotcount;
                $slots[] = [$j,$events[$j]['end_time']];
            }
            $events[$j]['place'] = $thisslot;
        }
        foreach ($slots as $slot) {
            $events[$slot[0]]['neighbors'] = $slotcount;
        }

        //foreach($events as $event) {var_dump($event);echo "<br />";}
        /*
            $selection = array();
            foreach ( $entries as $entry ) {
                $Hour = new \Calendar_Hour(2000,1,1,1);
                $Hour->setTimeStamp($entry['start_time']);

                // Create the decorator, passing it the Hour
                $event = new Event($Hour);

                // Attach the payload
                $event->setEntry($entry);

                // Add the decorator to the selection
                $selection[] = $event;
            }
            */
        $DayDecorator = new \DayEvent_Decorator($DayEvents);
        $DayDecorator->build($events);
        $data['Day'] = & $DayDecorator;
        $data['cal_sdow'] = CALENDAR_FIRST_DAY_OF_WEEK;
        return $data;
    }
}
