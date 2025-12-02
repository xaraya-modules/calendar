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
use Xaraya\Modules\Calendar\UserApi;
use Xaraya\Modules\MethodClass;
use Exception;

/**
 * calendar adminapi delete_calendar function
 * @extends MethodClass<AdminApi>
 */
class DeleteCalendarMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Delete a calendar from database
     * Usage : if ($adminapi->delete($calendar)) {...}
     * @param array<mixed> $args
     * @var mixed $calid ID of the calendar
     * @return bool|null true on success, false on failure
     * @see AdminApi::deleteCalendar()
     */
    public function __invoke(array $args = [])
    {
        /** @var AdminApi $adminapi */
        $adminapi = $this->adminapi();
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        // Get arguments from argument array
        extract($args);

        // Argument check
        if (!isset($calid)) {
            $msg = $this->ml(
                'Invalid #(1) for #(2) function #(3)() in module #(4)',
                'calendar ID',
                'admin',
                'delete',
                'Calendar'
            );
            throw new Exception($msg);
        }

        // TODO: Security check
        /*
            if (!$this->mod()->apiLoad('calendar', 'user')) return;

            $args['mask'] = 'DeleteCalendars';
            if (!$userapi->checksecurity($args)) {
                $msg = $this->ml('Not authorized to delete #(1) items',
                            'Calendar');
                throw new Exception($msg);
            }
        */
        // Call delete hooks for categories, hitcount etc.
        $args['module'] = 'calendar';
        $args['itemid'] = $calid;
        $this->mod()->callHooks('item', 'delete', $calid, $args);

        // Get database setup
        $dbconn = $this->db()->getConn();
        $xartable = & $this->db()->getTables();
        $calendarstable = $xartable['calendars'];
        $cal_filestable = $xartable['calendars_files'];
        $calfiles = $xartable['calfiles'];

        // Get files associated with that calendar
        $query = "SELECT xar_files_id FROM $cal_filestable
                 WHERE xar_calendars_id = ? LIMIT 1 ";
        $result = $dbconn->Execute($query, [$calid]);
        if (!$result) {
            return;
        }

        while ($result->next()) {
            // there should be only one result
            [$file_id] = $result -> fields;
        }

        if (isset($file_id) || !empty($file_id)) {
            $query = "DELETE FROM $calfiles
                      WHERE xar_id = ?";
            $result = $dbconn->Execute($query, [$file_id]);
            if (!$result) {
                return;
            }
        }

        // Delete item
        $query = "DELETE FROM $calendarstable
                  WHERE xar_id = ?";
        $result = $dbconn->Execute($query, [$calid]);
        if (!$result) {
            return;
        }

        $query = "DELETE FROM $cal_filestable
                  WHERE xar_calendars_id = ?";
        $result = $dbconn->Execute($query, [$calid]);
        if (!$result) {
            return;
        }

        $result -> Close();

        return true;
    }
}
