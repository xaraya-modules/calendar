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
use Xaraya\Modules\Calendar\UserApi;
use Xaraya\Modules\MethodClass;
use Query;
use sys;

sys::import('xaraya.modules.method');

/**
 * calendar user month function
 * @extends MethodClass<UserGui>
 */
class MonthMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see UserGui::month()
     */

    public function __invoke(array $args = [])
    {
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        $data = $userapi->getUserDateTimeInfo();

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

        $q = new Query('SELECT');
        $a[] = $q->plt('start_time', $start_time);
        $a[] = $q->pge('start_time + duration', $start_time);
        $b[] = $q->plt('start_time', $end_time);
        $b[] = $q->pge('start_time + duration', $end_time);
        $c[] = $q->pgt('start_time', $start_time);
        $c[] = $q->ple('start_time + duration', $end_time);

        $d[] = $q->pqand($a);
        $d[] = $q->pqand($b);
        $d[] = $q->pqand($c);
        $q->qor($d);

        $q->eq('role_id', $this->user()->getId());
        $data['conditions'] = $q;

        return $data;
    }
}
