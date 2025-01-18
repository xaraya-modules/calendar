<?php

/**
 * Handle module installer functions
 *
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar;

use Xaraya\Modules\InstallerClass;
use xarDB;
use xarMasks;
use xarPrivileges;
use xarModVars;
use xarMod;
use xarModHooks;
use xarTableDDL;
use Query;
use sys;
use Exception;

sys::import('xaraya.modules.installer');

/**
 * Handle module installer functions
 *
 * @todo add extra use ...; statements above as needed
 * @todo replaced calendar_*() function calls with $this->*() calls
 * @extends InstallerClass<Module>
 */
class Installer extends InstallerClass
{
    /**
     * Configure this module - override this method
     *
     * @todo use this instead of init() etc. for standard installation
     * @return void
     */
    public function configure()
    {
        $this->objects = [
            // add your DD objects here
            //'calendar_object',
        ];
        $this->variables = [
            // add your module variables here
            'hello' => 'world',
        ];
        $this->oldversion = '2.4.1';
    }

    /** xarinit.php functions imported by bermuda_cleanup */

    /**
     * Calendar Module
     * @package modules
     * @subpackage calendar module
     * @category Third Party Xaraya Module
     * @version 1.0.0
     * @copyright (C) copyright-placeholder
     * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
     * @author Marc Lutolf <mfl@netspan.ch>
     */
    public function init()
    {
        # --------------------------------------------------------
        #
        # Set up tables
        #
        sys::import('xaraya.structures.query');
        $q = new Query();
        $prefix = xarDB::getPrefix();

        $query = "DROP TABLE IF EXISTS " . $prefix . "_calendar_calendar";
        if (!$q->run($query)) {
            return;
        }
        $query = "CREATE TABLE " . $prefix . "_calendar_calendar (
          id          integer unsigned NOT NULL auto_increment,
          name        varchar(60) default '' NOT NULL,
          description text,
          module_id   integer unsigned default null,
          itemtype    integer unsigned default null,
          item_id     integer unsigned default null,
        PRIMARY KEY  (id)
        )";
        if (!$q->run($query)) {
            return;
        }

        $query = "DROP TABLE IF EXISTS " . $prefix . "_calendar_event";
        if (!$q->run($query)) {
            return;
        }
        $query = "CREATE TABLE " . $prefix . "_calendar_event (
          id                   integer unsigned NOT NULL auto_increment,
          name                 varchar(64) NULL,
          description          text,
          start_time           integer NULL,
          duration             integer NULL,
          end_time             integer NULL,
          recurring_code       integer unsigned NULL,
          recurring_span       integer unsigned NULL,
          start_location       varchar(254) NULL,
          end_location         varchar(254) NULL,
          object_id            integer unsigned NULL,
          module_id            integer unsigned NULL,
          itemtype             integer unsigned NULL,
          item_id              integer unsigned NULL,
          role_id              integer unsigned NULL,
          return_link          varchar(254) NULL,
          state                tinyint unsigned default 0 NOT NULL,
          timestamp            integer default 0 NOT NULL,
          PRIMARY KEY (id),
          KEY i_start (start_time),
          KEY i_end   (end_time)
        )";
        if (!$q->run($query)) {
            return;
        }

        /*    $query = "DROP TABLE IF EXISTS " . $prefix . "_bookings_repeat";
            if (!$q->run($query)) return;
            $query = "CREATE TABLE " . $prefix . "_bookings_repeat (
              id          integer unsigned NOT NULL auto_increment,
              start_time  int DEFAULT '0' NOT NULL,
              end_time    int DEFAULT '0' NOT NULL,
              rep_type    int DEFAULT '0' NOT NULL,
              end_date    int DEFAULT '0' NOT NULL,
              rep_opt     varchar(32) DEFAULT '' NOT NULL,
              objectid     int DEFAULT '1' NOT NULL,
              timestamp integer default 0 NOT NULL,
              owner integer default 0 NOT NULL,
              name        varchar(80) DEFAULT '' NOT NULL,
              status integer default 0 NOT NULL,
              description text,
              rep_num_weeks smallint NULL,

              PRIMARY KEY (id)
            )";
            if (!$q->run($query)) return;
        */

        # --------------------------------------------------------
        #
        # Set up masks
        #
        xarMasks::register('ViewCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_OVERVIEW');
        xarMasks::register('ReadCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_READ');
        xarMasks::register('CommentCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_COMMENT');
        xarMasks::register('ModerateCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_MODERATE');
        xarMasks::register('EditCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_EDIT');
        xarMasks::register('AddCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_ADD');
        xarMasks::register('ManageCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_DELETE');
        xarMasks::register('AdminCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_ADMIN');

        # --------------------------------------------------------
        #
        # Set up privileges
        #
        xarPrivileges::register('ViewCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_OVERVIEW');
        xarPrivileges::register('ReadCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_READ');
        xarPrivileges::register('CommentCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_COMMENT');
        xarPrivileges::register('ModerateCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_MODERATE');
        xarPrivileges::register('EditCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_EDIT');
        xarPrivileges::register('AddCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_ADD');
        xarPrivileges::register('ManageCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_DELETE');
        xarPrivileges::register('AdminCalendar', 'All', 'calendar', 'All', 'All', 'ACCESS_ADMIN');

        # --------------------------------------------------------
        #
        # Set up modvars
        #

        // Location of the PEAR Calendar Classes
        // Use the PHP Include path for now
        $this->mod()->setVar('pearcalendar_root', sys::code() . 'modules/calendar/pear/Calendar/');

        // get list of calendar ics files
        $data = xarMod::apiFunc('calendar', 'admin', 'get_calendars');
        $this->mod()->setVar('default_cal', serialize($data['icsfiles']));

        // Other variables from phpIcalendar config.inc.php
        $this->mod()->setVar('minical_view', 'week');
        //    $this->mod()->setVar('cal_sdow'               , 0);   // 0=sunday $week_start_day in phpIcalendar
        //    $this->mod()->setVar('day_start'              , '0700');
        //    $this->mod()->setVar('day_end'                , '2300');
        //    $this->mod()->setVar('gridLength'             , 15);
        $this->mod()->setVar('num_years', 1);
        $this->mod()->setVar('month_event_lines', 1);
        $this->mod()->setVar('tomorrows_events_lines', 1);
        $this->mod()->setVar('allday_week_lines', 1);
        $this->mod()->setVar('week_events_lines', 1);
        $this->mod()->setVar('second_offset', 0);
        $this->mod()->setVar('bleed_time', 0);
        $this->mod()->setVar('display_custom_goto', 0);
        $this->mod()->setVar('display_ical_list', 1);
        $this->mod()->setVar('allow_webcals', 0);
        $this->mod()->setVar('this_months_events', 1);
        $this->mod()->setVar('use_color_cals', 1);
        $this->mod()->setVar('daysofweek_dayview', 0);
        $this->mod()->setVar('enable_rss', 1);
        $this->mod()->setVar('show_search', 1);
        $this->mod()->setVar('allow_preferences', 1);
        $this->mod()->setVar('printview_default', 0);
        $this->mod()->setVar('show_todos', 1);
        $this->mod()->setVar('show_completed', 0);
        $this->mod()->setVar('allow_login', 0);

        // Regulate display in day view
        $this->mod()->setVar('windowwidth', 902);
        $this->mod()->setVar('minutesperunit', 15);
        $this->mod()->setVar('unitheight', 12);

        $this->mod()->setVar('event_duration', 60 * 60);
        $this->mod()->setVar('cal_sdow', 0);
        $this->mod()->setVar('day_start', 25200);
        $this->mod()->setVar('day_end', 82800);

        //TODO::Register the Module Variables
        //
        //$this->mod()->setVar('allowUserCalendars',false);
        //$this->mod()->setVar('eventsOpenNewWindow',false);
        //$this->mod()->setVar('adminNotify',false);
        //$this->mod()->setVar('adminEmail','none@none.org');

        # --------------------------------------------------------
        #  Register block types
        #
        xarMod::apiFunc('blocks', 'admin', 'register_block_type', ['modName' => 'calendar','blockType' => 'calnav']);
        xarMod::apiFunc('blocks', 'admin', 'register_block_type', ['modName' => 'calendar','blockType' => 'month']);

        //TODO::Register our blocklayout tags to allow using Objects in the templates
        //<xar:calendar-decorator object="$Month" decorator="Xaraya" name="$MonthURI"/>
        //<xar:calendar-build object="$Month"/>
        //<xar:set name="Month">& $Year->fetch()</xar:set>

        $this->mod()->setVar('SupportShortURLs', true);

        /*    xarTplRegisterTag(
                'calendar', 'calendar-decorator', array(),
                'calendar_userapi_handledecoratortag'
            );
            */

        # --------------------------------------------------------
        #
        # Set up hooks
        #

        xarModHooks::register('item', 'create', 'API', 'calendar', 'admin', 'hookcreate');
        xarModHooks::register('item', 'update', 'API', 'calendar', 'admin', 'hookupdate');
        //    xarModHooks::register('item', 'delete', 'API','calendar', 'admin', 'hookdelete');

        # --------------------------------------------------------
        #
        # Create DD objects
        #
        $module = 'calendar';
        $objects = [
            'calendar_calendar',
            'calendar_event',
        ];

        if (!xarMod::apiFunc('modules', 'admin', 'standardinstall', ['module' => $module, 'objects' => $objects])) {
            return;
        }

        return true;
    }

    /**
     * Module Upgrade Function
     */
    public function upgrade($oldversion)
    {
        switch ($oldversion) {
            case '0.1.0':
                // Start creating the tables

                $dbconn = xarDB::getConn();
                $xartable = & xarDB::getTables();
                $calfilestable = $xartable['calendars_files'];
                sys::import('xaraya.tableddl');
                $fields = [
                    'xar_calendars_id' => ['type' => 'integer', 'unsigned' => true, 'null' => false, 'primary_key' => true],
                    'xar_files_id' => ['type' => 'integer', 'unsigned' => true, 'null' => false, 'primary_key' => true],
                ];
                $query = xarTableDDL::createTable($calfilestable, $fields);
                if (empty($query)) {
                    return;
                }
                $result = &$dbconn->Execute($query);
                if (!$result) {
                    return;
                }

                $filestable = $xartable['calfiles'];
                sys::import('xaraya.tableddl');
                $fields = [
                    'xar_id' => ['type' => 'integer', 'unsigned' => true, 'null' => false, 'increment' => true, 'primary_key' => true],
                    'xar_path' => ['type' => 'varchar', 'size' => '255', 'null' => true],
                ];
                $query = xarTableDDL::createTable($filestable, $fields);
                if (empty($query)) {
                    return;
                }
                $result = &$dbconn->Execute($query);
                if (!$result) {
                    return;
                }

                $index = [
                    'name'      => 'i_' . xarDB::getPrefix() . '_calendars_files_calendars_id',
                    'fields'    => ['xar_calendars_id'],
                    'unique'    => false,
                ];
                $query = xarTableDDL::createIndex($calfilestable, $index);
                $result = $dbconn->Execute($query);
                if (!$result) {
                    return;
                }

                $index = [
                    'name'      => 'i_' . xarDB::getPrefix() . '_calendars_files_files_id',
                    'fields'    => ['xar_files_id'],
                    'unique'    => false,
                ];
                $query = xarTableDDL::createIndex($calfilestable, $index);
                $result = $dbconn->Execute($query);
                if (!$result) {
                    return;
                }

                return $this->upgrade('0.1.1');
        }
        return true;
    }

    /**
     * Module Delete Function
     */
    public function delete()
    {
        # --------------------------------------------------------
        #
        # Remove block types
        #
        if (!xarMod::apiFunc('blocks', 'admin', 'unregister_block_type', ['modName'  => 'calendar', 'blockType' => 'month'])) {
            return;
        }

        return xarMod::apiFunc('modules', 'admin', 'standarddeinstall', ['module' => 'calendar']);
    }
}
