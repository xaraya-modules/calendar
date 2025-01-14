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
use xarSecurity;
use xarModVars;
use xarServer;
use xarMod;
use xarController;
use sys;
use BadParameterException;

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
     */
    public function __invoke(array $args = [])
    {
        // Xaraya security
        if (!$this->checkAccess('ManageCalendar')) {
            return;
        }

        if (xarModVars::get('modules', 'disableoverview') == 0) {
            return [];
        } else {
            $redirect = $this->getModVar('defaultbackpage');
            if (!empty($redirect)) {
                $truecurrenturl = xarServer::getCurrentURL([], false);
                $urldata = xarMod::apiFunc('roles', 'user', 'parseuserhome', ['url' => $redirect,'truecurrenturl' => $truecurrenturl]);
                $this->redirect($urldata['redirecturl']);
            } else {
                $this->redirect($this->getUrl('admin', 'view'));
            }
        }
        return true;
    }
}
