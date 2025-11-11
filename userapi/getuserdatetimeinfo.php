<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\UserApi;

use Xaraya\Modules\Calendar\UserApi;
use Xaraya\Modules\MethodClass;

/**
 * calendar userapi getUserDateTimeInfo function
 * @extends MethodClass<UserApi>
 */
class GetUserDateTimeInfoMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Calendar Module
     * @package modules
     * @subpackage calendar module
     * @category Third Party Xaraya Module
     * @version 1.0.0
     * @copyright (C) copyright-placeholder
     * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
     * @author Marc Lutolf <mfl@netspan.ch>
     * @see UserApi::getUserDateTimeInfo()
     */
    public function __invoke(array $args = [])
    {
        // dates come in as YYYYMMDD
        $this->var()->get('cal_date', $cal_date, 'str:4:8', $this->mls()->formatDate('%Y%m%d'));

        $data = [];
        $data['cal_date'] = & $cal_date;

        if (!preg_match('/([\d]{4,4})([\d]{2,2})?([\d]{2,2})?/', $cal_date, $match)) {
            $year = $this->mls()->formatDate('Y');
            $month = $this->mls()->formatDate('m');
            $day = $this->mls()->formatDate('d');
        } else {
            $year = $match[1];
            if (isset($match[2])) {
                $month = $match[2];
            } else {
                $month = '01';
            }
            if (isset($match[3])) {
                $day = $match[3];
            } else {
                $day = '01';
            }
        }

        //$data['selected_date']   = (int) $year.$month.$day;
        $data['cal_day']    = (int) $day;
        $data['cal_month']  = (int) $month;
        $data['cal_year']   = (int) $year;
        //$data['selected_timestamp'] = gmmktime(0,0,0,$month,$day,$year);

        $today = new \XarDateTime();
        $usertz = $this->mod('roles')->getUserVar('usertimezone');
        $useroffset = $today->getTZOffset($usertz);
        $data['now'] = getdate(time() + $useroffset);
        return $data;
    }
}
