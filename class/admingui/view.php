<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\AdminGui;


use Xaraya\Modules\Calendar\AdminGui;
use Xaraya\Modules\MethodClass;
use xarSecurity;
use xarVar;
use xarModVars;
use xarSession;
use xarServer;
use DataObjectFactory;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar admin view function
 * @extends MethodClass<AdminGui>
 */
class ViewMethod extends MethodClass
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
        if (!$this->checkAccess('ManageCalendar')) {
            return;
        }

        $modulename = 'calendar';

        // Define which object will be shown
        if (!$this->fetch('objectname', 'str', $objectname, 'calendar_calendar', xarVar::DONT_SET)) {
            return;
        }
        if (!empty($objectname)) {
            xarModVars::set($modulename, 'defaultmastertable', $objectname);
        }

        // Set a return url
        xarSession::setVar('ddcontext.' . $modulename, ['return_url' => xarServer::getCurrentURL()]);

        // Get the available dropdown options
        $object = DataObjectFactory::getObjectList(['objectid' => 1]);
        $data['objectname'] = xarModVars::get($modulename, 'defaultmastertable');
        $items = $object->getItems();
        $options = [];
        foreach ($items as $item) {
            if (strpos($item['name'], $modulename) !== false) {
                $options[] = ['id' => $item['name'], 'name' => $item['name']];
            }
        }
        $data['options'] = $options;
        return $data;
    }
}
