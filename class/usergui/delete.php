<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar\UserGui;


use Xaraya\Modules\Calendar\UserGui;
use Xaraya\Modules\MethodClass;
use xarVar;
use xarSecurity;
use xarMod;
use xarSession;
use xarSec;
use xarTpl;
use xarController;
use xarModVars;
use DataObjectFactory;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user delete function
 * @extends MethodClass<UserGui>
 */
class DeleteMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    public function __invoke(array $args = [])
    {
        extract($args);

        if (!$this->fetch('objectid', 'isset', $objectid, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('name', 'isset', $name, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('itemid', 'id', $itemid)) {
            return;
        }
        if (!$this->fetch('confirm', 'isset', $confirm, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('noconfirm', 'isset', $noconfirm, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('join', 'isset', $join, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('table', 'isset', $table, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('tplmodule', 'isset', $tplmodule, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('template', 'isset', $template, null, xarVar::DONT_SET)) {
            return;
        }
        if (!$this->fetch('return_url', 'isset', $return_url, null, xarVar::DONT_SET)) {
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
            $data['authid'] = $this->genAuthKey();
            $data['object'] = $myobject;
            $data['context'] ??= $this->getContext();

            if (file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/user-delete.xd') ||
                file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/admin-delete-' . $data['template'] . '.xd')) {
                return xarTpl::module($data['tplmodule'], 'user', 'delete', $data, $data['template']);
            } else {
                return xarTpl::module('calendar', 'user', 'delete', $data, $data['template']);
            }
        }

        // If we get here it means that the user has confirmed the action

        if (!$this->confirmAuthKey()) {
            return;
        }

        $itemid = $myobject->deleteItem();
        if (!empty($return_url)) {
            $this->redirect($return_url);
        } else {
            $default = $this->getModVar('default_view');
            $this->redirect($this->getUrl(
                'user',
                $default,
                [
                    'page' => $default,
                ]
            ));
        }
        return true;
    }
}
