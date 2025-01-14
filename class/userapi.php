<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar;

use Xaraya\Modules\UserApiClass;
use xarLocale;
use xarMod;
use xarModVars;
use xarVar;
use xarController;
use sys;

sys::import('xaraya.modules.userapi');

/**
 * Handle the calendar user API
 *
 * @method mixed createUserDateTime(array $args)
 * @method mixed dayis(array $args)
 * @method mixed decodeShorturl(array $args)
 * @method mixed encodeShorturl(array $args)
 * @method mixed factory(array $args)
 * @method mixed get(array $args)
 * @method mixed getUserDateTimeInfo(array $args)
 * @method mixed getall(array $args)
 * @method mixed getdaylink(array $args)
 * @method mixed getevents(array $args)
 * @method mixed getitemtypes(array $args)
 * @method mixed getmenulinks(array $args)
 * @method mixed getmonthlink(array $args)
 * @method mixed getmonthnamelong(array $args)
 * @method mixed getmonthnameshort(array $args)
 * @method mixed getmonthstructure(array $args)
 * @method mixed getWeekLink(array $args)
 * @method mixed getWeekNumber(array $args)
 * @method mixed getyearlink(array $args)
 * @method mixed init(array $args)
 * @method mixed next(array $args)
 * @method mixed prev(array $args)
 * @extends UserApiClass<Module>
 */
class UserApi extends UserApiClass
{
    /**
     *  Used to get the current view the calendar is in (Day, Week, Month, Year)
     */
    public function currentView(array $args = [], $context = null)
    {
        $this->fetch('func', 'str::', $func, 'main', xarVar::NOT_REQUIRED);
        $valid = ['day','week','month','year'];
        $func = strtolower($func);
        if (!in_array($func, $valid)) {
            return $this->getModVar('default_view');
        } else {
            return $func;
        }
    }


    public function buildURL($args = [])
    {
        extract($args);
        unset($args);

        return $this->getUrl(
            'user',
            $cal_view,
            ['cal_date' => $cal_date]
        );
    }


    public function currentMonthURL(array $args = [], $context = null)
    {
        return $this->buildURL(
            [
                'cal_view' => 'month',
                'cal_date' => xarLocale::formatDate('%Y%m%d'),
            ]
        );
    }

    public function currentWeekURL(array $args = [], $context = null)
    {
        return $this->buildURL(
            [
                'cal_view' => 'week',
                'cal_date' => xarLocale::formatDate('%Y%m%d'),
            ]
        );
    }

    public function currentDayURL(array $args = [], $context = null)
    {
        return $this->buildURL(
            [
                'cal_view' => 'day',
                'cal_date' => xarLocale::formatDate('%Y%m%d'),
            ]
        );
    }

    public function currentYearURL(array $args = [], $context = null)
    {
        return $this->buildURL(
            [
                'cal_view' => 'year',
                'cal_date' => xarLocale::formatDate('%Y%m'),
            ]
        );
    }

    /**
     * Factory method that allows the creation of new objects
     *  @version $Id: factory.php,v 1.5 2003/06/24 21:30:30 roger Exp $
     * @param string $class the name of the object to create
     */
    public function factory(string $class)
    {
        static $calobject;
        static $icalobject;
        static $eventobject;
        static $importobject;
        static $exportobject;
        static $alarmobject;
        static $modinfo;

        if (!isset($modinfo)) {
            $modInfo = xarMod::getInfo(xarMod::getRegID('calendar'));
        }
        if (is_array($class)) {
            if (!empty($args['class'])) {
                // ['class' => '...']
                extract($args);
            } elseif (!empty($args[0])) {
                // ['...']
                $class = $args[0];
            } else {
                $class = '';
            }
        }

        switch (strtolower($class)) {
            case 'calendar':
                if (!isset($calobject)) {
                    sys::import("modules.$modInfo[osdirectory].class.calendar");
                    $calobject = new \Xaraya\Modules\Calendar\Calendar();
                }
                return $calobject;

            case 'ical_parser':
                // @todo use johngrogg/ics-parser or sabre/vobject package
                if (!isset($icalobject)) {
                    // @todo no idea where this is now
                    sys::import("modules.$modInfo[osdirectory].class.ical_parser");
                    $icalobject = new \Xaraya\Modules\Calendar\iCal_Parser();
                }
                return $icalobject;

            case 'event':
                if (!isset($eventobject)) {
                    // @todo needs a Calendar argument
                    sys::import("modules.$modInfo[osdirectory].class.event");
                    $eventobject = new \Xaraya\Modules\Calendar\Event();
                }
                return $eventobject;

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
        }
    }
}
