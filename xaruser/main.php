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

function calendar_user_main(array $args = [], $context = null)
{
    // Xaraya security
    if (!xarSecurity::check('ReadCalendar')) {
        return;
    }

    $redirect = xarModVars::get('calendar', 'frontend_page');
    if (!empty($redirect)) {
        $truecurrenturl = xarServer::getCurrentURL([], false);
        $urldata = xarMod::apiFunc('roles', 'user', 'parseuserhome', ['url' => $redirect,'truecurrenturl' => $truecurrenturl]);
        xarController::redirect($urldata['redirecturl'], null, $context);
    } else {
        xarController::redirect(xarController::URL('calendar', 'user', 'week'), null, $context);
    }
    return true;
}
