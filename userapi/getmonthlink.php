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
use xarController;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi getmonthlink function
 * @extends MethodClass<UserApi>
 */
class GetmonthlinkMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * calendar_userapi_getMonthLink
     *  Create a valid link to a particluar month
     *  @version $Id: getmonthlink.php,v 1.1 2003/06/24 20:01:14 roger Exp $
     * @author Roger Raymond
     *  @access public
     *  @param string $date YYYYMMDD date to provide link to
     *  @return string a valid link based on $this->ctl()->getModuleURL()
     *  @todo add necessary get vars to the resulting URL
     * @see UserApi::getmonthlink()
     */
    public function __invoke($date = null)
    {
        if (!isset($date)) {
            $date = date('Ymd');
        }
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $day = substr($date, 6, 2);

        $link = $this->mod()->getURL( 'user', 'month', ['cal_date' => $date]);
        return $link;
    }
}
