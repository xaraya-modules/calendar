<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\AdminApi;

use Xaraya\Modules\MethodClass;
use xarSecurity;
use xarController;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar adminapi getmenulinks function
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
        $menulinks = [];
        if (xarSecurity::check('AdminCalendar', 0)) {
            $menulinks[] = ['url'   => xarController::URL(
                'calendar',
                'admin',
                'view'
            ),
                'title' => xarML('Manage the Master Tables  of this module'),
                'label' => xarML('Master Tables'), ];
            $menulinks[] = ['url'   => xarController::URL(
                'calendar',
                'admin',
                'modifyconfig'
            ),
                'title' => xarML('Modify the configuration settings'),
                'label' => xarML('Modify Config'), ];

            /*
                $menulinks[] = Array(
                    'url'=>xarController::URL('calendar','admin','add_event'),
                    'title'=>xarML('Add a new calendar event'),
                    'label'=>xarML('Add event')
                    );
                $menulinks[] = Array(
                    'url'=>xarController::URL('calendar','admin','view'),
                    'title'=>xarML('View queued events'),
                    'label'=>xarML('View Queue')
                    );
                */
        }

        return $menulinks;
    }
}
