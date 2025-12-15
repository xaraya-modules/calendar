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
use IDNotFoundException;

/**
 * calendar adminapi hookupdate function
 * @extends MethodClass<AdminApi>
 */
class HookupdateMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see AdminApi::hookupdate()
     */

    public function __invoke(array $data = [])
    {
        if (!isset($data['extrainfo']) || !is_array($data['extrainfo'])) {
            $data['extrainfo'] = [];
        }

        // When called via hooks, modname will be empty, but we get it from the
        // extrainfo or the current module
        if (empty($data['module'])) {
            if (!empty($data['extrainfo']['module'])) {
                $data['module'] = $data['extrainfo']['module'];
            } else {
                $data['module'] = $this->req()->getModule();
            }
        }
        $data['module_id'] = $this->mod()->getID(($data['module']));
        if (empty($data['module_id'])) {
            throw new IDNotFoundException("module id for " . $data['modname']);
        }

        if (!isset($data['itemtype']) || !is_numeric($data['itemtype'])) {
            if (isset($data['extrainfo']['itemtype']) && is_numeric($data['extrainfo']['itemtype'])) {
                $data['itemtype'] = $data['extrainfo']['itemtype'];
            } else {
                $data['itemtype'] = 0;
            }
        }
        if (!isset($data['itemid']) || !is_numeric($data['itemid'])) {
            if (isset($data['extrainfo']['item_id']) && is_numeric($data['extrainfo']['item_id'])) {
                $data['itemid'] = $data['extrainfo']['item_id'];
            } else {
                $data['itemid'] = 0;
            }
        }

        $data['extrainfo']['module_id'] = $data['module_id'];
        $data['extrainfo']['itemtype'] = $data['itemtype'];
        $data['extrainfo']['item_id'] = $data['itemid'];

        $data['extrainfo']['name'] ??= $this->ml('Unknown Event');
        $data['extrainfo']['start_time'] ??= time();
        $data['extrainfo']['duration'] ??= 60;
        $data['extrainfo']['end_time'] ??= $data['extrainfo']['start_time'] + $data['extrainfo']['duration'];
        $data['extrainfo']['recurring_code'] ??= 0;
        $data['extrainfo']['recurring_span'] ??= 0;

        $data['extrainfo']['start_location'] ??= null;
        $data['extrainfo']['end_location'] ??= null;
        $data['extrainfo']['object_id'] ??= 0;
        $data['extrainfo']['role_id'] ??= $this->user()->getId();
        $data['extrainfo']['return_link'] ??= '';
        $data['extrainfo']['state'] ??= 3;
        $data['extrainfo']['timestamp'] ??= time();

        $object = $this->data()->getObject(['name' => 'calendar_event']);
        $item = $object->updateItem($data['extrainfo']);

        return $data['extrainfo'];
    }
}
