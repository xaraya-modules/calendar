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

/**
 * Delete an item
 * @package modules
 * @copyright (C) copyright-placeholder
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://www.xaraya.com
 *
 * @subpackage Dynamic Data module
 * @link http://xaraya.com/index.php/release/182.html
 * @author mikespub <mikespub@xaraya.com>
 */
/**
 * delete item
 * @param 'itemid' the id of the item to be deleted
 * @param 'confirm' confirm that this item can be deleted
 */

sys::import('modules.dynamicdata.class.objects.factory');

function calendar_user_delete(array $args = [], $context = null)
{
    extract($args);

    if (!xarVar::fetch('objectid', 'isset', $objectid, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('name', 'isset', $name, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('itemid', 'id', $itemid)) {
        return;
    }
    if (!xarVar::fetch('confirm', 'isset', $confirm, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('noconfirm', 'isset', $noconfirm, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('join', 'isset', $join, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('table', 'isset', $table, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('tplmodule', 'isset', $tplmodule, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('template', 'isset', $template, null, xarVar::DONT_SET)) {
        return;
    }
    if (!xarVar::fetch('return_url', 'isset', $return_url, null, xarVar::DONT_SET)) {
        return;
    }

    $myobject = DataObjectFactory::getObject(['objectid' => $objectid,
                                         'name'       => $name,
                                         'join'       => $join,
                                         'table'      => $table,
                                         'itemid'     => $itemid,
                                         'tplmodule'  => $tplmodule,
                                         'template'   => $template,
                                         'extend'     => false, ]);  //Note: this means we only delete this extension, not the parent
    if (empty($myobject)) {
        return;
    }
    $data = $myobject->toArray();

    // Security check
    if (!xarSecurity::check('DeleteDynamicDataItem', 1, 'Item', $data['moduleid'] . ":" . $data['itemtype'] . ":" . $data['itemid'])) {
        return;
    }

    // recover any session var information and remove it from the var
    $data = array_merge($data, xarMod::apiFunc('dynamicdata', 'user', 'getcontext', ['module' => $tplmodule]));
    //xarSession::setVar('ddcontext.' . $tplmodule, array('tplmodule' => $tplmodule));
    extract($data);

    $myobject->getItem();

    if (empty($confirm)) {
        $data['authid'] = xarSec::genAuthKey();
        $data['object'] = $myobject;
        $data['context'] ??= $context;

        if (file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/user-delete.xd') ||
            file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/admin-delete-' . $data['template'] . '.xd')) {
            return xarTpl::module($data['tplmodule'], 'user', 'delete', $data, $data['template']);
        } else {
            return xarTpl::module('calendar', 'user', 'delete', $data, $data['template']);
        }
    }

    // If we get here it means that the user has confirmed the action

    if (!xarSec::confirmAuthKey()) {
        return;
    }

    $itemid = $myobject->deleteItem();
    if (!empty($return_url)) {
        xarController::redirect($return_url, null, $context);
    } else {
        $default = xarModVars::get('calendar', 'default_view');
        xarController::redirect(xarController::URL(
            'calendar',
            'user',
            $default,
            [
                'page' => $default,
            ]
        ), null, $context);
    }
    return true;
}
