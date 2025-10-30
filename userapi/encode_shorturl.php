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

sys::import('xaraya.modules.method');

/**
 * calendar userapi encode_shorturl function
 * @extends MethodClass<UserApi>
 */
class EncodeShorturlMethod extends MethodClass
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
     * @see UserApi::encodeShorturl()
     */
    public function __invoke(array $params = [])
    {
        // Get arguments from argument array
        //extract($args); unset($args);
        // check if we have something to work with
        if (!isset($params['func'])) {
            return;
        }
        $day = $month = $year = null;
        // default path is empty -> no short URL
        $path = '';
        $extra = '';
        // we can't rely on $this->mod()->getName() here (yet) !
        $module = 'calendar';
        if (isset($params['cal_date']) && !empty($params['cal_date'])) {
            $year = substr($params['cal_date'], 0, 4);
            $month = substr($params['cal_date'], 4, 2);
            $day = substr($params['cal_date'], 6, 2);
        }
        if (empty($year)) {
            $year  = $this->mls()->formatDate('%Y');
        }
        if (empty($month)) {
            $month = $this->mls()->formatDate('%m');
        }
        if (empty($day)) {
            $day   = $this->mls()->formatDate('%d');
        }


        // specify some short URLs relevant to your module
        switch ($params['func']) {
            case 'main':
                $path = "/$module/";
                break;

            case 'day':
                $path = "/$module/$year$month$day/";
                break;

            case 'week': // it would be nice if this could be shorter, but probably not
                $path = "/$module/week/$year$month$day/";
                break;

            case 'month':
                $path = "/$module/$year$month/";
                break;

            case 'year':
                $path = "/$module/$year/";
                break;

            case 'modifyconfig':
                $path = "/$module/modifyconfig/";
                break;

                /*
                case 'submit':
                    $path = "/$module/submit/$year$month$day/";
                    break;

                case 'edit':
                    $path = "/$module/edit/";
                    if(isset($params['cal_eid']) && !empty($params['cal_eid'])) {
                        $path .= \xarVarPrep::forDisplay($params['cal_eid']).'.html/';
                    }
                    break;

                case 'publish':
                    $path = "/$module/publish/";
                    if(isset($params['calname']) && !empty($params['calname'])) {
                        $path .= \xarVarPrep::forDisplay($params['calname']).'.ics';
                    }
                    break;
                */
        }

        /*
        if(!empty($path) && isset($params['cal_sdow'])) {
            $join = empty($extra) ? '?' : '&amp;';
            $extra .= $join . 'cal_sdow=' . $params['cal_sdow'];
        }
        */

        if (!empty($path) && isset($params['cal_category'])) {
            $join = empty($extra) ? '?' : '&amp;';
            $extra .= $join . 'cal_category=' . $params['cal_category'];
        }

        if (!empty($path) && isset($params['cal_topic'])) {
            $join = empty($extra) ? '?' : '&amp;';
            $extra .= $join . 'cal_topic=' . $params['cal_topic'];
        }


        return $path . $extra;
    }
}
