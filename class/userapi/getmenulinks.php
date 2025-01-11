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
use xarModUserVars;
use xarLocale;
use xarController;
use xarUser;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getmenulinks function
 * @extends MethodClass<UserApi>
 */
class GetmenulinksMethod extends MethodClass
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
        xarVar::fetch('cal_sdow', 'int::', $cal_sdow, xarModUserVars::get('calendar', 'cal_sdow'));
        xarVar::fetch('cal_date', 'int::', $cal_date, xarLocale::formatDate('%Y%m%d'));

        $menulinks[] = ['url'   => xarController::URL('calendar', 'user', 'day', ['cal_date' => $cal_date]),
            'title' => xarML('Day'),
            'label' => xarML('Day'), ];

        $menulinks[] = ['url'   => xarController::URL('calendar', 'user', 'week', ['cal_date' => $cal_date]),
            'title' => xarML('Week'),
            'label' => xarML('Week'), ];

        $menulinks[] = ['url'   => xarController::URL('calendar', 'user', 'month', ['cal_date' => $cal_date]),
            'title' => xarML('Month'),
            'label' => xarML('Month'), ];

        $menulinks[] = ['url'   => xarController::URL('calendar', 'user', 'year', ['cal_date' => $cal_date]),
            'title' => xarML('Year'),
            'label' => xarML('Year'), ];

        if (xarUser::isLoggedIn()) {
            $menulinks[] = ['url' => xarController::URL('calendar', 'user', 'modifyconfig'),
                'title' => xarML('Modify Config'),
                'label' => xarML('Modify Config'), ];
        }

        return $menulinks;
    }
}
