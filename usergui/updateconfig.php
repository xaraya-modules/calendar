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

/**
 * calendar user updateconfig function
 * @extends MethodClass<UserGui>
 */
class UpdateconfigMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Allows a user to modify their Calendar specific changes
     * @see UserGui::updateconfig()
     */
    public function __invoke(array $args = [])
    {
        $this->var()->get('cal_sdow', $cal_sdow, 'int:0:6', $this->mod()->getUserVar('cal_sdow'));
        $this->mod()->setUserVar('cal_sdow', $cal_sdow);

        $this->var()->get('default_view', $default_view, 'str::', $this->mod()->getUserVar('default_view'));
        $this->mod()->setUserVar('default_view', $default_view);

        $this->ctl()->redirect($this->mod()->getURL('user', 'modifyconfig'));
    }
}
