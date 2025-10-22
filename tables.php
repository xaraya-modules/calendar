<?php

namespace Xaraya\Modules\Calendar;

class Tables
{
    /**
     * Calendar Module
     *
     * @package modules
     * @subpackage calendar module
     * @category Third Party Xaraya Module
     * @version 1.0.0
     * @copyright (C) copyright-placeholder
     * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
     * @author Marc Lutolf <mfl@netspan.ch>
     */
    public function __invoke(string $prefix = 'xar')
    {
        $xartables = [];
        $prefix = $prefix . '_calendar';

        $xartables['calendar_calendar'] = $prefix . '_calendar';
        $xartables['calendar_event'] = $prefix . '_event';

        return $xartables;
    }
}
