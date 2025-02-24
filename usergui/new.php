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
 * calendar user new function
 * @extends MethodClass<UserGui>
 */
class NewMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Create a new item of the event object
     * @see UserGui::new()
     */
    public function __invoke(array $args = [])
    {
        if (!$this->sec()->checkAccess('AddCalendar')) {
            return;
        }

        $this->var()->find('page', $data['page'], 'str:1', 'week');
        $this->session()->setVar('ddcontext.calendar', ['page' => $data['page']]);
        $data['object'] = $this->data()->getObject(['name' => 'calendar_event']);
        $data['tplmodule'] = 'calendar';
        $data['authid'] = $this->sec()->genAuthKey();
        return $data;
    }
}
