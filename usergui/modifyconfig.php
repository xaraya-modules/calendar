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
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user modifyconfig function
 * @extends MethodClass<UserGui>
 */
class ModifyconfigMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Allows a user to modify their Calendar specific changes
     * @see UserGui::modifyconfig()
     */
    public function __invoke(array $args = [])
    {
        $this->var()->get('cal_sdow', $cal_sdow, 'int:0:6', $this->mod()->getUserVar('cal_sdow'));
        $this->var()->get('default_view', $default_view, 'int:0:6', $this->mod()->getUserVar('default_view'));
        return [
            'cal_sdow' => $cal_sdow,
            'default_view' => $default_view,
        ];
    }
}
