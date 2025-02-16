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

use Xaraya\Modules\ModuleClass;

/**
 * Get calendar module classes via xarMod::getModule()
 */
class Module extends ModuleClass
{
    public function setClassTypes(): void
    {
        parent::setClassTypes();
        // add other class types for calendar
        //$this->classtypes['utilapi'] = 'UtilApi';
        if (!defined('CALENDAR_ROOT')) {
            define('CALENDAR_ROOT', __DIR__ . '/pear/Calendar/');
        }
    }
}
