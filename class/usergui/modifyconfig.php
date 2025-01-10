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

use Xaraya\Modules\MethodClass;
use xarVar;
use xarModUserVars;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user modifyconfig function
 */
class ModifyconfigMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Allows a user to modify their Calendar specific changes
     */
    public function __invoke(array $args = [])
    {
        xarVar::fetch('cal_sdow', 'int:0:6', $cal_sdow, xarModUserVars::get('calendar', 'cal_sdow'));
        xarVar::fetch('default_view', 'int:0:6', $default_view, xarModUserVars::get('calendar', 'default_view'));
        return [
            'cal_sdow' => $cal_sdow,
            'default_view' => $default_view,
        ];
    }
}
