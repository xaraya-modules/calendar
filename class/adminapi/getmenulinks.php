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


use Xaraya\Modules\Calendar\AdminApi;
use Xaraya\Modules\MethodClass;
use xarSecurity;
use xarController;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar adminapi getmenulinks function
 * @extends MethodClass<AdminApi>
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
        if ($this->sec()->checkAccess('AdminCalendar', 0)) {
            $menulinks[] = ['url'   => $this->mod()->getURL('admin', 'view'),
                'title' => $this->ml('Manage the Master Tables  of this module'),
                'label' => $this->ml('Master Tables'), ];
            $menulinks[] = ['url'   => $this->mod()->getURL('admin', 'modifyconfig'),
                'title' => $this->ml('Modify the configuration settings'),
                'label' => $this->ml('Modify Config'), ];

            /*
                $menulinks[] = Array(
                    'url'=>$this->mod()->getURL('admin', 'add_event'),
                    'title'=>$this->ml('Add a new calendar event'),
                    'label'=>$this->ml('Add event')
                    );
                $menulinks[] = Array(
                    'url'=>$this->mod()->getURL('admin', 'view'),
                    'title'=>$this->ml('View queued events'),
                    'label'=>$this->ml('View Queue')
                    );
                */
        }

        return $menulinks;
    }
}
