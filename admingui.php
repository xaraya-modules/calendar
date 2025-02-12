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

use Xaraya\Modules\AdminGuiClass;
use sys;

sys::import('xaraya.modules.admingui');
sys::import('modules.calendar.adminapi');

/**
 * Handle the calendar admin GUI
 *
 * @method mixed create(array $args)
 * @method mixed main(array $args)
 * @method mixed modifyconfig(array $args)
 * @method mixed update(array $args)
 * @method mixed view(array $args)
 * @extends AdminGuiClass<Module>
 */
class AdminGui extends AdminGuiClass
{
    // ...
}
