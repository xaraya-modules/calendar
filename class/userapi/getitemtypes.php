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
use xarController;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getitemtypes function
 * @extends MethodClass<UserApi>
 */
class GetitemtypesMethod extends MethodClass
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
        $itemtypes = [];

        $itemtypes[1] = ['label' => $this->translate('Event'),
            'title' => $this->translate('View Event'),
            'url'   => $this->getUrl('user', 'view'),
        ];
        $itemtypes[2] = ['label' => $this->translate('ToDo'),
            'title' => $this->translate('View ToDo'),
            'url'   => $this->getUrl('user', 'view'),
        ];
        $itemtypes[3] = ['label' => $this->translate('Alarm'),
            'title' => $this->translate('View Alarm'),
            'url'   => $this->getUrl('user', 'view'),
        ];
        $itemtypes[4] = ['label' => $this->translate('FreeBusy'),
            'title' => $this->translate('View FreeBusy'),
            'url'   => $this->getUrl('user', 'view'),
        ];
        // @todo let's use DataObjectFactory::getModuleItemType here, but not until roles brings in dd automatically
        $extensionitemtypes = xarMod::apiFunc('dynamicdata', 'user', 'getmoduleitemtypes', ['moduleid' => 7, 'native' => false]);

        $keys = array_merge(array_keys($itemtypes), array_keys($extensionitemtypes));
        $values = array_merge(array_values($itemtypes), array_values($extensionitemtypes));
        return array_combine($keys, $values);
    }
}
