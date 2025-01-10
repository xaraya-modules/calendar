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

use Xaraya\Modules\MethodClass;
use xarVar;
use xarLocale;
use xarModUserVars;
use xarSession;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getuserdatetimeinfo function
 */
class GetuserdatetimeinfoMethod extends MethodClass
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
     */
    public function __invoke(array $args = [])
    {
        // dates come in as YYYYMMDD
        xarVar::fetch('cal_date', 'str:4:8', $cal_date, xarLocale::formatDate('%Y%m%d'));

        $data = [];
        $data['cal_date'] = & $cal_date;

        if (!preg_match('/([\d]{4,4})([\d]{2,2})?([\d]{2,2})?/', $cal_date, $match)) {
            $year = xarLocaleFormateDate('Y');
            $month = xarLocaleFormateDate('m');
            $day = xarLocaleFormateDate('d');
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

        sys::import('xaraya.structures.datetime');
        $today = new XarDateTime();
        $usertz = xarModUserVars::get('roles', 'usertimezone', xarSession::getVar('role_id'));
        $useroffset = $today->getTZOffset($usertz);
        $data['now'] = getdate(time() + $useroffset);
        return $data;
    }
}
