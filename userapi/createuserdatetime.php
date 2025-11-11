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

/**
 * calendar userapi createUserDateTime function
 * @extends MethodClass<UserApi>
 */
class CreateUserDateTimeMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * calendar_userapi_createUserDateTime
     *  return the date/time for a user based on timezone/locale
     *  @version $Id: createuserdatetime.php,v 1.1 2003/06/24 20:01:14 roger Exp $
     * @author Roger Raymond
     *  @param string $format valid date/time format using php's date() function
     *  @return string valid date/time
     *  @todo user timezone modifications
     * @see UserApi::createUserDateTime()
     */
    public function __invoke($format = 'Ymd')
    {
        return gmdate($format);

        /*
        if(xarUserLoggedIn()) {
            // $tzoffest = user's timezone offset
        } else {
            // $tzoffset = site's timezone offset
        }
        */
    }
}
