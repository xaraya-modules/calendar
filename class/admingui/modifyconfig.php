<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\AdminGui;


use Xaraya\Modules\Calendar\AdminGui;
use Xaraya\Modules\MethodClass;
use xarMod;
use xarSecurity;
use xarVar;
use xarSec;
use xarModVars;
use xarTpl;
use xarModItemVars;
use xarController;
use xarModHooks;
use DataPropertyMaster;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar admin modifyconfig function
 * @extends MethodClass<AdminGui>
 */
class ModifyconfigMethod extends MethodClass
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
     */
    public function __invoke(array $args = [])
    {
        $data = xarMod::apiFunc('calendar', 'admin', 'menu');
        $data = array_merge($data, xarMod::apiFunc('calendar', 'admin', 'get_calendars'));
        if (!$this->checkAccess('AdminCalendar')) {
            return;
        }

        if (!$this->fetch('phase', 'str:1:100', $phase, 'modify', xarVar::NOT_REQUIRED, xarVar::PREP_FOR_DISPLAY)) {
            return;
        }
        if (!$this->fetch('tab', 'str:1:100', $data['tab'], 'general', xarVar::NOT_REQUIRED)) {
            return;
        }

        $data['module_settings'] = xarMod::apiFunc('base', 'admin', 'getmodulesettings', ['module' => 'calendar']);
        $data['module_settings']->setFieldList('items_per_page, use_module_alias, module_alias_name, enable_short_urls', 'use_module_icons, frontend_page, backend_page');
        $data['module_settings']->getItem();

        switch (strtolower($phase)) {
            case 'modify':
            default:
                switch ($data['tab']) {
                    case 'calendar_general':
                        sys::import('modules.calendar.pear.Calendar.Util.Textual');
                        $data['weekdays'] = \Calendar_Util_Textual::weekdayNames();
                        break;
                }

                break;

            case 'update':
                // Confirm authorisation code
                if (!$this->confirmAuthKey()) {
                    return;
                }
                if (!$this->fetch('windowwidth', 'int:1', $windowwidth, $this->getModVar('aliasname'), xarVar::NOT_REQUIRED)) {
                    return;
                }
                if (!$this->fetch('minutesperunit', 'int:1', $minutesperunit, $this->getModVar('minutesperunit'), xarVar::NOT_REQUIRED)) {
                    return;
                }
                if (!$this->fetch('unitheight', 'int:1', $unitheight, $this->getModVar('unitheight'), xarVar::NOT_REQUIRED)) {
                    return;
                }

                if (!$this->fetch('default_view', 'str:1', $default_view, $this->getModVar('default_view'), xarVar::NOT_REQUIRED)) {
                    return;
                }
                if (!$this->fetch('cal_sdow', 'str:1', $cal_sdow, $this->getModVar('cal_sdow'), xarVar::NOT_REQUIRED)) {
                    return;
                }

                $isvalid = $data['module_settings']->checkInput();
                if (!$isvalid) {
                    $data['context'] ??= $this->getContext();
                    return xarTpl::module('calendar', 'admin', 'modifyconfig', $data);
                } else {
                    $itemid = $data['module_settings']->updateItem();
                }

                sys::import('modules.dynamicdata.class.properties.master');
                $timeproperty = DataPropertyMaster::getProperty(['type' => 'formattedtime']);
                $day_start = $timeproperty->checkInput('day_start') ? $timeproperty->getValue() : $this->getModVar('day_start');
                $day_end = $timeproperty->checkInput('day_end') ? $timeproperty->getValue() : $this->getModVar('day_end');

                if ($data['tab'] == 'calendar_general') {
                    $this->setModVar('items_per_page', $items_per_page);
                    $this->setModVar('supportshorturls', $shorturls);
                    $this->setModVar('useModuleAlias', $useModuleAlias);
                    $this->setModVar('aliasname', $aliasname);
                    $this->setModVar('windowwidth', $windowwidth);
                    $this->setModVar('minutesperunit', $minutesperunit);
                    $this->setModVar('unitheight', $unitheight);

                    $this->setModVar('default_view', $default_view);
                    $this->setModVar('cal_sdow', $cal_sdow);
                    $this->setModVar('day_start', $day_start);
                    $this->setModVar('day_end', $day_end);
                }
                $regid = xarMod::getRegID($tabmodule);
                xarModItemVars::set('calendar', 'windowwidth', $windowwidth, $regid);
                xarModItemVars::set('calendar', 'minutesperunit', $minutesperunit, $regid);
                xarModItemVars::set('calendar', 'unitheight', $unitheight, $regid);

                xarModItemVars::set('calendar', 'default_view', $default_view, $regid);
                xarModItemVars::set('calendar', 'cal_sdow', $cal_sdow, $regid);
                xarModItemVars::set('calendar', 'day_start', $day_start, $regid);
                xarModItemVars::set('calendar', 'day_end', $day_end, $regid);

                $this->redirect($this->getUrl('admin', 'modifyconfig', ['tab' => $data['tab']]));
                return true;
                break;
        }

        // Initialise the $data variable that will hold the data to be used in
        // the blocklayout template, and get the common menu configuration - it
        // helps if all of the module pages have a standard menu at the top to
        // support easy navigation

        // Variables from phpIcalendar config.inc.php
        $data['default_view'] = $this->getModVar('default_view');
        $data['minical_view'] = $this->getModVar('minical_view');
        $data['default_cal'] = unserialize($this->getModVar('default_cal'));
        $data['cal_sdow']         = $this->getModVar('cal_sdow');
        $data['week_start_day']         = $this->getModVar('week_start_day');
        $data['day_start']              = $this->getModVar('day_start');
        $data['day_end']                = $this->getModVar('day_end');
        $data['gridLength']             = $this->getModVar('gridLength');
        $data['num_years']              = $this->getModVar('num_years');
        $data['month_event_lines']      = $this->getModVar('month_event_lines');
        $data['tomorrows_events_lines'] = $this->getModVar('tomorrows_events_lines');
        $data['allday_week_lines']      = $this->getModVar('allday_week_lines');
        $data['week_events_lines']      = $this->getModVar('week_events_lines');
        $data['second_offset']          = $this->getModVar('second_offset');
        $data['bleed_time']             = $this->getModVar('bleed_time');

        $data['display_custom_goto']    = $this->getModVar('display_custom_goto');
        $data['display_custom_gotochecked'] = $this->getModVar('display_custom_goto') ? 'checked' : '';
        $data['display_ical_list']      = $this->getModVar('display_ical_list');
        $data['display_ical_listchecked'] = $this->getModVar('display_ical_list') ? 'checked' : '';
        $data['allow_webcals']          = $this->getModVar('allow_webcals');
        $data['allow_webcalschecked'] = $this->getModVar('allow_webcals') ? 'checked' : '';
        $data['this_months_events']     = $this->getModVar('this_months_events');
        $data['this_months_eventschecked'] = $this->getModVar('this_months_events') ? 'checked' : '';
        $data['use_color_cals']         = $this->getModVar('use_color_cals');
        $data['use_color_calschecked'] = $this->getModVar('use_color_cals') ? 'checked' : '';
        $data['daysofweek_dayview']     = $this->getModVar('daysofweek_dayview');
        $data['daysofweek_dayviewchecked'] = $this->getModVar('daysofweek_dayview') ? 'checked' : '';
        $data['enable_rss']             = $this->getModVar('enable_rss');
        $data['enable_rsschecked'] = $this->getModVar('enable_rss') ? 'checked' : '';
        $data['show_search']            = $this->getModVar('show_search');
        $data['show_searchchecked'] = $this->getModVar('show_search') ? 'checked' : '';
        $data['allow_preferences']      = $this->getModVar('allow_preferences');
        $data['allow_preferenceschecked'] = $this->getModVar('allow_preferences') ? 'checked' : '';
        $data['printview_default']      = $this->getModVar('printview_default');
        $data['printview_defaultchecked'] = $this->getModVar('printview_default') ? 'checked' : '';
        $data['show_todos']             = $this->getModVar('show_todos');
        $data['show_todoschecked'] = $this->getModVar('show_todos') ? 'checked' : '';
        $data['show_completed']         = $this->getModVar('show_completed');
        $data['show_completedchecked'] = $this->getModVar('show_completed') ? 'checked' : '';
        $data['allow_login']            = $this->getModVar('allow_login');
        $data['allow_loginchecked'] = $this->getModVar('allow_login') ? 'checked' : '';

        /*
        //  list of options from config.inc.php not included
        $style_sheet            = 'silver';         // Themes support - silver, red, green, orange, grey, tan
        $language               = 'English';        // Language support - 'English', 'Polish', 'German', 'French', 'Dutch', 'Danish', 'Italian', 'Japanese', 'Norwegian', 'Spanish', 'Swedish', 'Portuguese', 'Catalan', 'Traditional_Chinese', 'Esperanto', 'Korean'
        $calendar_path          = '';               // Leave this blank on most installs, place your full path to calendars if they are outside the phpicalendar folder.
        $tmp_dir                = '/tmp';           // The temporary directory on your system (/tmp is fine for UNIXes including Mac OS X)
        $cookie_uri             = '';               // The HTTP URL to the PHP iCalendar directory, ie. http://www.example.com/phpicalendar -- AUTO SETTING -- Only set if you are having cookie issues.
        $download_uri           = '';               // The HTTP URL to your calendars directory, ie. http://www.example.com/phpicalendar/calendars -- AUTO SETTING -- Only set if you are having subscribe issues.
        $default_path           = 'http://www.example.com/phpicalendar';                        // The HTTP URL to the PHP iCalendar directory, ie. http://www.example.com/phpicalendar
        $timezone               = '';               // Set timezone. Read TIMEZONES file for more information
        $save_parsed_cals       = 'yes';            // Recommended 'yes'. Saves a copy of the cal in /tmp after it's been parsed. Improves performence.
        */

        $data['updatebutton'] = xarVar::prepForDisplay($this->translate('Update Configuration'));
        // Note : if you don't plan on providing encode/decode functions for
        // short URLs (see xaruserapi.php), you should remove these from your
        // admin-modifyconfig.xard template !
        $data['shorturlslabel'] = $this->translate('Enable short URLs?');
        $data['shorturlschecked'] = $this->getModVar('SupportShortURLs') ?
        'checked' : '';


        /*    //TODO: should I include this stuff? --amoro
            $hooks = xarModHooks::call('module', 'modifyconfig', 'calendar',
                array('module' => 'calendar'));
            if (empty($hooks)) {
                $data['hooks'] = '';
            } elseif (is_array($hooks)) {
                $data['hooks'] = join('', $hooks);
            } else {
                $data['hooks'] = $hooks;
            }
        */
        $data['authid'] = $this->genAuthKey();
        return $data;
    }
}
