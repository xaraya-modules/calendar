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

use Xaraya\Modules\MethodClass;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user year function
 */
class YearMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    public function __invoke(array $args = [])
    {
        $data = xarMod::apiFunc('calendar', 'user', 'getUserDateTimeInfo');
        $Year = new \Calendar_Year($data['cal_year']);
        $Year->build(); // TODO: find a better way to handle this
        $data['Year'] = & $Year;
        $data['cal_sdow'] = CALENDAR_FIRST_DAY_OF_WEEK;
        return $data ;
    }
}
