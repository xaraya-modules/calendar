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

use Xaraya\Modules\UserGuiClass;
use sys;

sys::import('xaraya.modules.usergui');
sys::import('modules.calendar.userapi');

/**
 * Handle the calendar user GUI
 *
 * @method mixed day(array $args)
 * @method mixed delete(array $args)
 * @method mixed display(array $args)
 * @method mixed main(array $args)
 * @method mixed modify(array $args)
 * @method mixed modifyconfig(array $args)
 * @method mixed month(array $args)
 * @method mixed new(array $args)
 * @method mixed publish(array $args)
 * @method mixed submit(array $args)
 * @method mixed test(array $args)
 * @method mixed updateconfig(array $args)
 * @method mixed view(array $args)
 * @method mixed week(array $args)
 * @method mixed year(array $args)
 * @extends UserGuiClass<Module>
 */
class UserGui extends UserGuiClass
{
    // ...
}
