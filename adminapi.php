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

use Xaraya\Modules\AdminApiClass;

/**
 * Handle the calendar admin API
 *
 * @method mixed createCalendars(array $args)
 * @method mixed deleteCalendar(array $args)
 * @method mixed getCalendars(array $args)
 * @method mixed getconfighook(array $args)
 * @method mixed getmenulinks(array $args)
 * @method mixed hookcreate(array $args)
 * @method mixed hookupdate(array $args)
 * @method mixed menu(array $args = [])
 * @extends AdminApiClass<Module>
 */
class AdminApi extends AdminApiClass
{
    // ...
}
