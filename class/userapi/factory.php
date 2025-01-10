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

use Xaraya\Modules\MethodClass;
use xarMod;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi factory function
 */
class FactoryMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Factory method that allows the creation of new objects
     *  @version $Id: factory.php,v 1.5 2003/06/24 21:30:30 roger Exp $
     * @param string $class the name of the object to create
     */
    public function __invoke($class)
    {
        static $calobject;
        static $icalobject;
        static $eventobject;
        static $importobject;
        static $exportobject;
        static $alarmobject;
        static $modinfo;

        if (!isset($modinfo)) {
            $modInfo = & xarMod::getInfo(xarMod::getRegID('calendar'));
        }

        switch (strtolower($class)) {
            case 'calendar':
                if (!isset($calobject)) {
                    sys::import("modules.$modInfo[osdirectory].class.calendar");
                    $calobject = new \Xaraya\Modules\Calendar\Calendar();
                }
                return $calobject;
                break;

            case 'ical_parser':
                if (!isset($icalobject)) {
                    sys::import("modules.$modInfo[osdirectory].class.ical_parser");
                    $icalobject = new \Xaraya\Modules\Calendar\iCal_Parser();
                }
                return $icalobject;
                break;

            case 'event':
                if (!isset($eventobject)) {
                    sys::import("modules.$modInfo[osdirectory].class.event");
                    $eventobject = new \Xaraya\Modules\Calendar\Event();
                }
                return $eventobject;
                break;

                /*
                case 'import':
                    break;

                case 'export':
                    break;

                case 'alarm':
                    break;
                */
            default:
                return;
                break;
        }
    }
}
