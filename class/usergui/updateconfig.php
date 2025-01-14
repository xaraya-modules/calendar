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
use xarVar;
use xarModUserVars;
use xarController;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user updateconfig function
 * @extends MethodClass<UserGui>
 */
class UpdateconfigMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Allows a user to modify their Calendar specific changes
     */
    public function __invoke(array $args = [])
    {
        $this->fetch('cal_sdow', 'int:0:6', $cal_sdow, xarModUserVars::get('calendar', 'cal_sdow'));
        xarModUserVars::set('calendar', 'cal_sdow', $cal_sdow);

        $this->fetch('default_view', 'str::', $default_view, xarModUserVars::get('calendar', 'default_view'));
        xarModUserVars::set('calendar', 'default_view', $default_view);

        $this->redirect($this->getUrl('user', 'modifyconfig'));
    }
}
