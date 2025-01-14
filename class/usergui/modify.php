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
use xarSession;
use xarSec;
use DataObjectFactory;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user modify function
 * @extends MethodClass<UserGui>
 */
class ModifyMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Modify an item of the event object
     */
    public function __invoke(array $args = [])
    {
        if (!$this->checkAccess('EditCalendar')) {
            return;
        }

        if (!$this->fetch('itemid', 'int', $data['itemid'], 0, xarVar::NOT_REQUIRED)) {
            return;
        }
        if (!$this->fetch('page', 'str:1', $data['page'], 'week', xarVar::NOT_REQUIRED)) {
            return;
        }
        xarSession::setVar('ddcontext.calendar', ['page' => $data['page'],
        ]);
        $data['object'] = DataObjectFactory::getObject(['name' => 'calendar_event']);
        $data['object']->getItem(['itemid' => $data['itemid']]);
        $data['tplmodule'] = 'calendar';
        $data['authid'] = $this->genAuthKey();
        return $data;
    }
}
