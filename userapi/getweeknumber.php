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
use sys;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getWeekNumber function
 * @extends MethodClass<UserApi>
 */
class GetWeekNumberMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * temp placeholder function to make year view work
     * @see UserApi::getWeekNumber()
     */
    public function __invoke(array $args = [])
    {
        return 1;
    }
}
