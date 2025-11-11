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

/**
 * calendar user view function
 * @extends MethodClass<UserGui>
 */
class ViewMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see UserGui::view()
     */

    public function __invoke(array $args = [])
    {
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        $data = $userapi->getUserDateTimeInfo();
        $DayEvents = new \Calendar_Day($data['cal_year'], $data['cal_month'], $data['cal_day']);
        $args = [
            'day' => &$DayEvents,
        ];
        $events = $userapi->getevents($args);
        return $data;
    }
}
