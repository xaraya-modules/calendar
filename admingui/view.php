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
use sys;

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
     * @see AdminGui::view()
     */
    public function __invoke(array $args = [])
    {
        if (!$this->sec()->checkAccess('ManageCalendar')) {
            return;
        }

        $modulename = 'calendar';

        // Define which object will be shown
        $this->var()->check('objectname', $objectname, 'str', 'calendar_calendar');
        if (!empty($objectname)) {
            $this->mod($modulename)->setVar('defaultmastertable', $objectname);
        }

        // Set a return url
        $this->session()->setVar('ddcontext.' . $modulename, ['return_url' => $this->ctl()->getCurrentURL()]);

        // Get the available dropdown options
        $object = $this->data()->getObjectList(['objectid' => 1]);
        $data['objectname'] = $this->mod($modulename)->getVar('defaultmastertable');
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
