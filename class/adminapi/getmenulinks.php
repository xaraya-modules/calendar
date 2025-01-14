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
        if ($this->checkAccess('AdminCalendar', 0)) {
            $menulinks[] = ['url'   => $this->getUrl('admin', 'view'),
                'title' => $this->translate('Manage the Master Tables  of this module'),
                'label' => $this->translate('Master Tables'), ];
            $menulinks[] = ['url'   => $this->getUrl('admin', 'modifyconfig'),
                'title' => $this->translate('Modify the configuration settings'),
                'label' => $this->translate('Modify Config'), ];

            /*
                $menulinks[] = Array(
                    'url'=>$this->getUrl('admin', 'add_event'),
                    'title'=>$this->translate('Add a new calendar event'),
                    'label'=>$this->translate('Add event')
                    );
                $menulinks[] = Array(
                    'url'=>$this->getUrl('admin', 'view'),
                    'title'=>$this->translate('View queued events'),
                    'label'=>$this->translate('View Queue')
                    );
                */
        }

        return $menulinks;
    }
}
