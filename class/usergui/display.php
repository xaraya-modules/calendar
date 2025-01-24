<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\UserGui;


use Xaraya\Modules\Calendar\UserGui;
use Xaraya\Modules\MethodClass;
use xarSecurity;
use xarVar;
use xarSec;
use DataObjectFactory;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user display function
 * @extends MethodClass<UserGui>
 */
class DisplayMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Display an item of the event object
     */
    public function __invoke(array $args = [])
    {
        if (!$this->sec()->checkAccess('ReadCalendar')) {
            return;
        }

        if (!$this->var()->find('itemid', $data['itemid'], 'int', 0)) {
            return;
        }
        if (!$this->var()->find('page', $data['page'], 'str:1', 'week')) {
            return;
        }
        $data['object'] = $this->data()->getObject(['name' => 'calendar_event']);
        $data['object']->getItem(['itemid' => $data['itemid']]);
        $data['tplmodule'] = 'calendar';
        $data['authid'] = $this->sec()->genAuthKey();
        return $data;
    }
}
