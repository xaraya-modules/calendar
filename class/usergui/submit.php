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
use xarVar;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user submit function
 * @extends MethodClass<UserGui>
 */
class SubmitMethod extends MethodClass
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
        $this->var()->get('cal_sdow', $cal_sdow, 'int:0:6', 0);
        $this->var()->get('cal_date', $cal_date, 'int::', 0);

        /** @var UserApi $userapi */
        $userapi = $this->userapi();

        $c = $userapi->factory('calendar');
        $c->setStartDayOfWeek($cal_sdow);

        $data = $userapi->getUserDateTimeInfo();
        $data['cal_sdow'] = & $c->getStartDayOfWeek();
        $data['shortDayNames'] = & $c->getShortDayNames($c->getStartDayOfWeek());
        $data['mediumDayNames'] = & $c->getMediumDayNames($c->getStartDayOfWeek());
        $data['longDayNames'] = & $c->getLongDayNames($c->getStartDayOfWeek());
        $data['calendar'] = & $c;

        // return the event data
        $this->var()->get('event_id', $event_id, 'int::', 0);
        $e = $userapi->factory('event');
        $e->buildEvent($event_id);
        // remember to pass in the existing array so it can be appended too
        $e->getEventDataForBL($data);

        // echo the content to the screen
        return $data;
    }
}
