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
use Xaraya\Modules\Calendar\AdminApi;
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
     * @see AdminGui::modifyconfig()
     */
    public function __invoke(array $args = [])
    {
        /** @var AdminApi $adminapi */
        $adminapi = $this->adminapi();
        $data = $adminapi->menu();
        $data = array_merge($data, $adminapi->get_calendars());
        if (!$this->sec()->checkAccess('AdminCalendar')) {
            return;
        }

        $this->var()->find('phase', $phase, 'str:1:100', 'modify');
        $this->var()->find('tab', $data['tab'], 'str:1:100', 'general');

        $data['module_settings'] = $this->mod()->apiFunc('base', 'admin', 'getmodulesettings', ['module' => 'calendar']);
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
                if (!$this->sec()->confirmAuthKey()) {
                    return;
                }
                $this->var()->find('windowwidth', $windowwidth, 'int:1', $this->mod()->getVar('aliasname'));
                $this->var()->find('minutesperunit', $minutesperunit, 'int:1', $this->mod()->getVar('minutesperunit'));
                $this->var()->find('unitheight', $unitheight, 'int:1', $this->mod()->getVar('unitheight'));

                $this->var()->find('default_view', $default_view, 'str:1', $this->mod()->getVar('default_view'));
                $this->var()->find('cal_sdow', $cal_sdow, 'str:1', $this->mod()->getVar('cal_sdow'));

                $isvalid = $data['module_settings']->checkInput();
                if (!$isvalid) {
                    $data['context'] ??= $this->getContext();
                    return $this->mod()->template('modifyconfig', $data);
                } else {
                    $itemid = $data['module_settings']->updateItem();
                }

                sys::import('modules.dynamicdata.class.properties.master');
                $timeproperty = $this->prop()->getProperty(['type' => 'formattedtime']);
                $day_start = $timeproperty->checkInput('day_start') ? $timeproperty->getValue() : $this->mod()->getVar('day_start');
                $day_end = $timeproperty->checkInput('day_end') ? $timeproperty->getValue() : $this->mod()->getVar('day_end');

                if ($data['tab'] == 'calendar_general') {
                    $this->mod()->setVar('items_per_page', $items_per_page);
                    $this->mod()->setVar('supportshorturls', $shorturls);
                    $this->mod()->setVar('useModuleAlias', $useModuleAlias);
                    $this->mod()->setVar('aliasname', $aliasname);
                    $this->mod()->setVar('windowwidth', $windowwidth);
                    $this->mod()->setVar('minutesperunit', $minutesperunit);
                    $this->mod()->setVar('unitheight', $unitheight);

                    $this->mod()->setVar('default_view', $default_view);
                    $this->mod()->setVar('cal_sdow', $cal_sdow);
                    $this->mod()->setVar('day_start', $day_start);
                    $this->mod()->setVar('day_end', $day_end);
                }
                $regid = $this->mod()->getRegID($tabmodule);
                xarModItemVars::set('calendar', 'windowwidth', $windowwidth, $regid);
                xarModItemVars::set('calendar', 'minutesperunit', $minutesperunit, $regid);
                xarModItemVars::set('calendar', 'unitheight', $unitheight, $regid);

                xarModItemVars::set('calendar', 'default_view', $default_view, $regid);
                xarModItemVars::set('calendar', 'cal_sdow', $cal_sdow, $regid);
                xarModItemVars::set('calendar', 'day_start', $day_start, $regid);
                xarModItemVars::set('calendar', 'day_end', $day_end, $regid);

                $this->ctl()->redirect($this->mod()->getURL('admin', 'modifyconfig', ['tab' => $data['tab']]));
                return true;
                break;
        }

        // Initialise the $data variable that will hold the data to be used in
        // the blocklayout template, and get the common menu configuration - it
        // helps if all of the module pages have a standard menu at the top to
        // support easy navigation

        // Variables from phpIcalendar config.inc.php
        $data['default_view'] = $this->mod()->getVar('default_view');
        $data['minical_view'] = $this->mod()->getVar('minical_view');
        $data['default_cal'] = unserialize($this->mod()->getVar('default_cal'));
        $data['cal_sdow']         = $this->mod()->getVar('cal_sdow');
        $data['week_start_day']         = $this->mod()->getVar('week_start_day');
        $data['day_start']              = $this->mod()->getVar('day_start');
        $data['day_end']                = $this->mod()->getVar('day_end');
        $data['gridLength']             = $this->mod()->getVar('gridLength');
        $data['num_years']              = $this->mod()->getVar('num_years');
        $data['month_event_lines']      = $this->mod()->getVar('month_event_lines');
        $data['tomorrows_events_lines'] = $this->mod()->getVar('tomorrows_events_lines');
        $data['allday_week_lines']      = $this->mod()->getVar('allday_week_lines');
        $data['week_events_lines']      = $this->mod()->getVar('week_events_lines');
        $data['second_offset']          = $this->mod()->getVar('second_offset');
        $data['bleed_time']             = $this->mod()->getVar('bleed_time');

        $data['display_custom_goto']    = $this->mod()->getVar('display_custom_goto');
        $data['display_custom_gotochecked'] = $this->mod()->getVar('display_custom_goto') ? 'checked' : '';
        $data['display_ical_list']      = $this->mod()->getVar('display_ical_list');
        $data['display_ical_listchecked'] = $this->mod()->getVar('display_ical_list') ? 'checked' : '';
        $data['allow_webcals']          = $this->mod()->getVar('allow_webcals');
        $data['allow_webcalschecked'] = $this->mod()->getVar('allow_webcals') ? 'checked' : '';
        $data['this_months_events']     = $this->mod()->getVar('this_months_events');
        $data['this_months_eventschecked'] = $this->mod()->getVar('this_months_events') ? 'checked' : '';
        $data['use_color_cals']         = $this->mod()->getVar('use_color_cals');
        $data['use_color_calschecked'] = $this->mod()->getVar('use_color_cals') ? 'checked' : '';
        $data['daysofweek_dayview']     = $this->mod()->getVar('daysofweek_dayview');
        $data['daysofweek_dayviewchecked'] = $this->mod()->getVar('daysofweek_dayview') ? 'checked' : '';
        $data['enable_rss']             = $this->mod()->getVar('enable_rss');
        $data['enable_rsschecked'] = $this->mod()->getVar('enable_rss') ? 'checked' : '';
        $data['show_search']            = $this->mod()->getVar('show_search');
        $data['show_searchchecked'] = $this->mod()->getVar('show_search') ? 'checked' : '';
        $data['allow_preferences']      = $this->mod()->getVar('allow_preferences');
        $data['allow_preferenceschecked'] = $this->mod()->getVar('allow_preferences') ? 'checked' : '';
        $data['printview_default']      = $this->mod()->getVar('printview_default');
        $data['printview_defaultchecked'] = $this->mod()->getVar('printview_default') ? 'checked' : '';
        $data['show_todos']             = $this->mod()->getVar('show_todos');
        $data['show_todoschecked'] = $this->mod()->getVar('show_todos') ? 'checked' : '';
        $data['show_completed']         = $this->mod()->getVar('show_completed');
        $data['show_completedchecked'] = $this->mod()->getVar('show_completed') ? 'checked' : '';
        $data['allow_login']            = $this->mod()->getVar('allow_login');
        $data['allow_loginchecked'] = $this->mod()->getVar('allow_login') ? 'checked' : '';

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

        $data['updatebutton'] = $this->var()->prep($this->ml('Update Configuration'));
        // Note : if you don't plan on providing encode/decode functions for
        // short URLs (see xaruserapi.php), you should remove these from your
        // admin-modifyconfig.xard template !
        $data['shorturlslabel'] = $this->ml('Enable short URLs?');
        $data['shorturlschecked'] = $this->mod()->getVar('SupportShortURLs') ?
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
        $data['authid'] = $this->sec()->genAuthKey();
        return $data;
    }
}
