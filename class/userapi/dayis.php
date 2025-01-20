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
 * calendar userapi dayis function
 * @extends MethodClass<UserApi>
 */
class DayisMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Wrapper for the dayIs method of the calendar class
     *  @author Roger Raymond <roger@asphyxia.com>
     * @version $Id: dayis.php,v 1.2 2003/06/24 21:23:06 roger Exp $
     *  @param int $day 0 - 6 [Sun - Sat]
     *  @param int $date valid date YYYYMMDD
     *  @return bool true/false depending on day looking for and the date
     */
    public function __invoke(array $args = [])
    {
        extract($args);
        unset($args);
        // make sure we have a valid day value
        if (!$this->var()->validate('int:0:7', $day)) {
            return;
        }
        // TODO: Revisit this later and make a new validator for it
        // make sure we have a valid date
        if (!$this->var()->validate('int::', $date)) {
            return;
        }
        /** @var UserApi $userapi */
        $userapi = $this->userapi();

        $c = $userapi->factory('calendar');
        return $c->dayIs($day, $date);
    }
}
