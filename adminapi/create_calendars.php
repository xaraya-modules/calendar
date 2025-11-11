<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\AdminApi;

use Xaraya\Modules\Calendar\AdminApi;
use Xaraya\Modules\MethodClass;
use Exception;

/**
 * calendar adminapi create_calendars function
 * @extends MethodClass<AdminApi>
 */
class CreateCalendarsMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     *
     * @return int|null (calendar id on success, false on failure)
     * @see AdminApi::createCalendars()
     */
    public function __invoke(array $args = [])
    {
        extract($args);

        // argument check
        if (!isset($calname)) {
            $msg = $this->ml('Calendar name not specified', 'admin', 'create', 'calendar');
            throw new Exception($msg);
        }

        // TODO: should I move these two issets to the admin function
        // admin/create_calendars.php? --amoro
        if (!isset($mod_id)) {
            $module = $this->ctl()->getRequest()->getModule();
            $mod_id = $this->mod()->getRegID($module);
        }
        if (!isset($role_id)) {
            $role_id = $this->user()->getId();
        }

        // Load up database details.
        $dbconn = $this->db()->getConn();
        $xartable = & $this->db()->getTables();
        $caltable = $xartable['calendars'];

        // Insert instance details.
        $nextId = $dbconn->GenId($caltable);
        $query = 'INSERT INTO ' . $caltable . ' (
                  xar_id,
                  xar_role_id,
                  xar_mod_id,
                  xar_name
                ) VALUES (?, ?, ?, ?)';

        $result = $dbconn->Execute(
            $query,
            [
                $nextId, $role_id, $mod_id, $calname,
            ]
        );
        if (!$result) {
            return;
        }

        // Get ID of row inserted.
        $calendid = $dbconn->PO_Insert_ID($caltable, 'xar_id');

        // If not database type also add file info

        // Allow duplicate files here, to make it easier to delete them
        // WARNING: if somebody changes this you should also change the
        // delete function to avoid major dataloss!!! --amoro
        if ($addtype != 'db') {
            $filestable = $xartable['calfiles'];
            $cal_filestable = $xartable['calendars_files'];

            $nextID = $dbconn->GenId($filestable);
            $query = 'INSERT INTO ' . $filestable . ' (
                      xar_id,
                      xar_path
                    ) VALUES (?, ?)';
            $result = $dbconn->Execute(
                $query,
                [
                    $nextID,$fileuri,
                ]
            );

            // Get ID of row inserted.
            $fileid = $dbconn->PO_Insert_ID($filestable, 'xar_id');

            $query = 'INSERT INTO ' . $cal_filestable . ' (
                          xar_calendars_id,
                          xar_files_id
                        ) VALUES (?, ?)';
            $result = $dbconn->Execute(
                $query,
                [
                    $calendid,$fileid,
                ]
            );
        }
        return $calendid;
    }
}
