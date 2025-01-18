<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\UserApi;

use Xaraya\Modules\Calendar\UserApi;
use Xaraya\Modules\MethodClass;
use xarVar;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getmonthnameshort function
 * @extends MethodClass<UserApi>
 */
class GetmonthnameshortMethod extends MethodClass
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
        extract($args);
        unset($args);
        if (!isset($month)) {
            $month = date('m');
        }

        // make sure we have a valid month value
        if (!$this->var()->validate('int:1:12', $month)) {
            return;
        }
        $userapi = $this->getParent();

        $c = $userapi->factory('calendar');
        return $c->MonthShort($month);
    }
}
