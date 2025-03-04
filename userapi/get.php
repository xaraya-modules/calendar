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
use xarSecurity;
use xarDB;
use sys;
use Exception;

sys::import('xaraya.modules.method');

/**
 * calendar userapi get function
 * @extends MethodClass<UserApi>
 */
class GetMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * get a specific calendars
     * @package Xaraya eXtensible Management System
     * @copyright (C) 2002 by the Xaraya Development Team.
     * @license GPL <http://www.gnu.org/licenses/gpl.html>
     * @link http://www.xaraya.org
     * @subpackage calendar module
     * @author Andrea Moro
     * @Optional parameters
     * @param array<mixed> $args
     * @var mixed $calname name of calendar
     * @var mixed $calid id of calendar
     * @return array|null of calendar, or false on failure
     * @see UserApi::get()
     */
    public function __invoke(array $args = [])
    {
        extract($args);
        // Optional arguments
        if (!isset($calname) && (!isset($calid) || !is_numeric($calid))) {
            $msg = $this->ml(
                'Invalid Parameter #(1) for #(2) function #(3)() in module #(4)',
                'calid or calname',
                'userapi',
                'get',
                'calendar'
            );
            throw new Exception($msg);
        }

        // Security check
        //    if (!$this->sec()->checkAccess('ViewCalendars')) return;

        $calendars = [];
        $dbconn = $this->db()->getConn();
        $xartable = & $this->db()->getTables();
        $caltable = $xartable['calendars'];
        $cal_filestable = $xartable['calendars_files'];
        $filestable = $xartable['calfiles'];

        // defaults to getting $calname
        if (isset($calname)) {
            $where = " $caltable.xar_name = \"$calname\" ";
        } else {
            $where = " $caltable.xar_id = $calid ";
        }

        // TODO: cleanup query? --amoro
        $query = " SELECT DISTINCT $caltable.xar_id,
                                   $caltable.xar_name,
                                   $filestable.xar_path
                    FROM $caltable
                    LEFT JOIN $cal_filestable
                        ON $caltable.xar_id = $cal_filestable.xar_calendars_id
                    LEFT JOIN $filestable
                        ON $cal_filestable.xar_files_id = $filestable.xar_id
                    WHERE $where ";

        // Run the query
        $result = $dbconn->Execute($query);

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
