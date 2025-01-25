<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\AdminApi;


use Xaraya\Modules\Calendar\AdminApi;
use Xaraya\Modules\MethodClass;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar adminapi get_calendars function
 * @extends MethodClass<AdminApi>
 */
class GetCalendarsMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * generate the common admin menu configuration
     * @see AdminApi::getCalendars()
     */
    public function __invoke(array $args = [])
    {
        // Initialise the array that will hold the menu configuration
        $cals = [];

        $curdir = sys::varpath() . '/calendar';

        $ics_array = [];

        if ($dir = @opendir($curdir)) {
            while (($file = @readdir($dir)) !== false) {
                if (preg_match('/\.(ics)$/', $file)) {
                    $ics_array[] = $file;
                }
            }
        }

        $cals['icsfiles'] = $ics_array;
        $cals['thereAreIcs'] = sizeof($ics_array);

        // Return the array containing the menu configuration
        return $cals;
    }
}
