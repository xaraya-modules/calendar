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
use xarController;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi next function
 */
class NextMethod extends MethodClass
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
    public function __invoke($args = [])
    {
        xarVar::fetch('cal_sdow', 'int:0:7', $cal_sdow, 0);
        // what function are we in
        xarVar::fetch('func', 'str::', $func);

        extract($args);
        unset($args);

        if (!isset($cal_interval)) {
            $cal_interval = 1;
        }

        xarVar::validate('int::', $cal_date);
        xarVar::validate('int:1:', $cal_interval);
        xarVar::validate('str::', $cal_type);

        $y = substr($cal_date, 0, 4);
        $m = substr($cal_date, 4, 2);
        $d = substr($cal_date, 6, 2);

        switch (strtolower($cal_type)) {
            case 'day':
                $d += $cal_interval;
                break;

            case 'week':
                $d += (7 * $cal_interval);
                break;

            case 'month':
                $m += $cal_interval;
                break;

            case 'year':
                $y += $cal_interval;
                break;
        }

        $new_date = gmdate('Ymd', gmmktime(0, 0, 0, $m, $d, $y));
        return xarController::URL('calendar', 'user', strtolower($func), ['cal_date' => $new_date,'cal_sdow' => $cal_sdow]);
    }
}
