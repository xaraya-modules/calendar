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
use xarVar;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getmonthstructure function
 * @extends MethodClass<UserApi>
 */
class GetmonthstructureMethod extends MethodClass
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
        extract($args);
        unset($args);
        if (!isset($month)) {
            return;
        }
        if (!isset($year)) {
            return;
        }

        xarVar::validate('int:1:12', $month);
        xarVar::validate('int::', $year);
        xarVar::fetch('cal_sdow', 'int:0:6', $cal_sdow, 0);

        $c = xarMod::apiFunc('calendar', 'user', 'factory', 'calendar');
        $c->setStartDayOfWeek($cal_sdow);
        // echo the content to the screen
        return $c->getCalendarMonth($year . $month);
    }
}
