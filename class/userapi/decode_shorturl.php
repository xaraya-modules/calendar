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
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar userapi decode_shorturl function
 * @extends MethodClass<UserApi>
 */
class DecodeShorturlMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Calendar Module
     * @package modules
     * @subpackage calendar module
     * @category Third Party Xaraya Module
     * @version 1.0.0
     * @copyright (C) copyright-placeholder
     * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
     * @author Marc Lutolf <mfl@netspan.ch>
     * @see UserApi::decodeShorturl()
     */
    public function __invoke(array $params = [])
    {
        $args = [];
        $func = null;
        // the function is based on the date
        if (isset($params[1]) && preg_match('/(^[\d]{4,8})$/', $params[1])) {
            if (strlen($params[1]) == 8) {
                $func = 'day';
            } elseif (strlen($params[1]) == 6) {
                $func = 'month';
            } elseif (strlen($params[1]) == 4) {
                $func = 'year';
            }
            $args['cal_date'] = $params[1];
        } elseif (isset($params[1])) {
            $func = $params[1];
        }

        // if we don't have a function, call the default view
        if (!isset($func)) {
            return ['main', $args];
        } elseif ($func == 'publish') {
            if (!empty($params[2]) && preg_match('/^([\w -]+)\.ics$/', $params[2], $matches)) {
                $args['calname'] = $matches[1];
            }
            return ['publish',$args];
        }

        // check out the next set of parameters (
        for ($i = 2,$max = count($params); $i < $max; $i++) {
            if (preg_match('/(^[\d]{4,8})$/', $params[$i], $matches)) {
                // this is a date of some sort (YYYYMMDD)
                $args['cal_date'] = $matches[1];
            } elseif (preg_match('/([\w]+)/i', $params[$i], $matches)) {
                // this should be a username
                $args['cal_user'] = $matches[1];
            }
        }

        // return the decoded information
        return [$func,$args];
    }
}
