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
use sys;

sys::import('xaraya.modules.method');

/**
 * calendar admin main function
 * @extends MethodClass<AdminGui>
 */
class MainMethod extends MethodClass
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
     * @see AdminGui::main()
     */
    public function __invoke(array $args = [])
    {
        // Xaraya security
        if (!$this->sec()->checkAccess('ManageCalendar')) {
            return;
        }

        if (!$this->mod()->disableOverview()) {
            return [];
        } else {
            $redirect = $this->mod()->getVar('defaultbackpage');
            if (!empty($redirect)) {
                $truecurrenturl = $this->ctl()->getCurrentURL([], false);
                $urldata = $this->mod()->apiFunc('roles', 'user', 'parseuserhome', ['url' => $redirect,'truecurrenturl' => $truecurrenturl]);
                $this->ctl()->redirect($urldata['redirecturl']);
            } else {
                $this->ctl()->redirect($this->mod()->getURL('admin', 'view'));
            }
        }
        return true;
    }
}
