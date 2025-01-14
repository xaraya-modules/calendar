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
        $this->fetch('cal_sdow', 'int::', $cal_sdow, xarModUserVars::get('calendar', 'cal_sdow'));
        $this->fetch('cal_date', 'int::', $cal_date, xarLocale::formatDate('%Y%m%d'));

        $menulinks[] = ['url'   => $this->getUrl( 'user', 'day', ['cal_date' => $cal_date]),
            'title' => $this->translate('Day'),
            'label' => $this->translate('Day'), ];

        $menulinks[] = ['url'   => $this->getUrl( 'user', 'week', ['cal_date' => $cal_date]),
            'title' => $this->translate('Week'),
            'label' => $this->translate('Week'), ];

        $menulinks[] = ['url'   => $this->getUrl( 'user', 'month', ['cal_date' => $cal_date]),
            'title' => $this->translate('Month'),
            'label' => $this->translate('Month'), ];

        $menulinks[] = ['url'   => $this->getUrl( 'user', 'year', ['cal_date' => $cal_date]),
            'title' => $this->translate('Year'),
            'label' => $this->translate('Year'), ];

        if (xarUser::isLoggedIn()) {
            $menulinks[] = ['url' => $this->getUrl('user', 'modifyconfig'),
                'title' => $this->translate('Modify Config'),
                'label' => $this->translate('Modify Config'), ];
        }

        return $menulinks;
    }
}
