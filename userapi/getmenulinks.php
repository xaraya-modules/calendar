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
use sys;

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
     * @see UserApi::getmenulinks()
     */
    public function __invoke(array $args = [])
    {
        $this->var()->get('cal_sdow', $cal_sdow, 'int::', $this->mod()->getUserVar('cal_sdow'));
        $this->var()->get('cal_date', $cal_date, 'int::', $this->mls()->formatDate('%Y%m%d'));

        $menulinks[] = ['url'   => $this->mod()->getURL( 'user', 'day', ['cal_date' => $cal_date]),
            'title' => $this->ml('Day'),
            'label' => $this->ml('Day'), ];

        $menulinks[] = ['url'   => $this->mod()->getURL( 'user', 'week', ['cal_date' => $cal_date]),
            'title' => $this->ml('Week'),
            'label' => $this->ml('Week'), ];

        $menulinks[] = ['url'   => $this->mod()->getURL( 'user', 'month', ['cal_date' => $cal_date]),
            'title' => $this->ml('Month'),
            'label' => $this->ml('Month'), ];

        $menulinks[] = ['url'   => $this->mod()->getURL( 'user', 'year', ['cal_date' => $cal_date]),
            'title' => $this->ml('Year'),
            'label' => $this->ml('Year'), ];

        if ($this->user()->isLoggedIn()) {
            $menulinks[] = ['url' => $this->mod()->getURL('user', 'modifyconfig'),
                'title' => $this->ml('Modify Config'),
                'label' => $this->ml('Modify Config'), ];
        }

        return $menulinks;
    }
}
