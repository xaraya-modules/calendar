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
use xarDB;
use Query;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getevents function
 * @extends MethodClass<UserApi>
 */
class GeteventsMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    public function __invoke(array $args = [])
    {
        extract($args);
        $xartable = & xarDB::getTables();

        $q = new Query('SELECT');
        $q->addtable($xartable['calendar_event']);
        $q->ge('start_time', $day->thisDay(true));
        $q->lt('start_time', $day->nextDay(true));

        if (!$q->run()) {
            return;
        }
        return $q->output();
    }
}
