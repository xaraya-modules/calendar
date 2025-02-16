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
use xarMod;
use xarSession;
use Query;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user week function
 * @extends MethodClass<UserGui>
 */
class WeekMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see UserGui::week()
     */

    public function __invoke(array $args = [])
    {
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        $data = $userapi->getUserDateTimeInfo();

        $WeekEvents = new \Calendar_Week($data['cal_year'], $data['cal_month'], $data['cal_day'], $this->mod()->getVar('cal_sdow'));

        $start_time = $WeekEvents->thisWeek;
        $end_time = $WeekEvents->nextWeek;

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

        $q->eq('role_id', $this->session()->getUserId());
        $data['conditions'] = $q;

        return $data;
    }
}
