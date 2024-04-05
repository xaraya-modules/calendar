<?php
/**
 * Calendar Module
 *
 * @package modules
 * @subpackage calendar module
 * @category Third Party Xaraya Module
 * @version 1.0.0
 * @copyright (C) copyright-placeholder
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */

function calendar_admin_main(array $args = [], $context = null)
{
    // Xaraya security
    if (!xarSecurity::check('ManageCalendar')) {
        return;
    }

    if (xarModVars::get('modules', 'disableoverview') == 0) {
        return [];
    } else {
        $redirect = xarModVars::get('calendar', 'defaultbackpage');
        if (!empty($redirect)) {
            $truecurrenturl = xarServer::getCurrentURL([], false);
            $urldata = xarMod::apiFunc('roles', 'user', 'parseuserhome', ['url' => $redirect,'truecurrenturl' => $truecurrenturl]);
            xarController::redirect($urldata['redirecturl'], null, $context);
        } else {
            xarController::redirect(xarController::URL('calendar', 'admin', 'view'), null, $context);
        }
    }
    return true;
}
