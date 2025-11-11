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

/**
 * calendar userapi getall function
 * @extends MethodClass<UserApi>
 */
class GetallMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * get overview of all calendars
     * Note : the following parameters are all optional
     * @param array<mixed> $args
     * @var mixed $numitems number of articles to get
     * @var mixed $startnum starting article number
     * @return array|null of calendars, or false on failure
     * @see UserApi::getall()
     */
    public function __invoke(array $args = [])
    {
        extract($args);
        // Optional arguments
        if (!isset($startnum)) {
            $startnum = 1;
        }

        $calendars = [];

        // Security check
        //    if (!$this->sec()->checkAccess('ViewCalendars')) return;

        $dbconn = $this->db()->getConn();
        $xartable = & $this->db()->getTables();
        $caltable = $xartable['calendars'];
        $cal_filestable = $xartable['calendars_files'];
        $filestable = $xartable['calfiles'];

        // TODO: cleanup query? --amoro
        $query = " SELECT DISTINCT $caltable.xar_id,
                                   $caltable.xar_name,
                                   $filestable.xar_path
                    FROM $caltable
                    LEFT JOIN $cal_filestable
                        ON $caltable.xar_id = $cal_filestable.xar_calendars_id
                    LEFT JOIN $filestable
                        ON $cal_filestable.xar_files_id = $filestable.xar_id ";

        // Run the query
        if (isset($numitems) && is_numeric($numitems)) {
            $result = $dbconn->SelectLimit($query, $numitems, $startnum - 1);
        } else {
            $result = $dbconn->Execute($query);
        }
        if (!$result) {
            return;
        }

        while ($result->next()) {
            [$cid,
                $cname,
                $cpath] = $result->fields;
            $calendars[] = [  'cid' => $cid,'cname' => $cname,'cpath' => $cpath,
            ];
        }
        $result->Close();
        return $calendars;
    }
}
