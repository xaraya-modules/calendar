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
    /** functions imported by bermuda_cleanup * @see UserGui::delete()
     */

    public function __invoke(array $args = [])
    {
        extract($args);

        $this->var()->check('objectid', $objectid);
        $this->var()->check('name', $name);
        $this->var()->get('itemid', $itemid, 'id');
        $this->var()->check('confirm', $confirm);
        $this->var()->check('noconfirm', $noconfirm);
        $this->var()->check('join', $join);
        $this->var()->check('table', $table);
        $this->var()->check('tplmodule', $tplmodule);
        $this->var()->check('template', $template);
        $this->var()->check('return_url', $return_url);

        $myobject = $this->data()->getObject(['objectid' => $objectid,
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
        if (!$this->sec()->check('DeleteDynamicDataItem', 1, 'Item', $data['moduleid'] . ":" . $data['itemtype'] . ":" . $data['itemid'])) {
            return;
        }

        // recover any session var information and remove it from the var
        $data = array_merge($data, $this->mod()->apiFunc('dynamicdata', 'user', 'sessioncontext', ['module' => $tplmodule]));
        //$this->session()->setVar('ddcontext.' . $tplmodule, array('tplmodule' => $tplmodule));
        extract($data);

        $myobject->getItem();

        if (empty($confirm)) {
            $data['authid'] = $this->sec()->genAuthKey();
            $data['object'] = $myobject;
            $data['context'] ??= $this->getContext();

            if (file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/user-delete.xd') ||
                file_exists('code/modules/' . $data['tplmodule'] . '/xartemplates/admin-delete-' . $data['template'] . '.xd')) {
                return $this->tpl()->module($data['tplmodule'], 'user', 'delete', $data, $data['template']);
            } else {
                return $this->mod()->template('delete', $data, $data['template']);
            }
        }

        // If we get here it means that the user has confirmed the action

        if (!$this->sec()->confirmAuthKey()) {
            return;
        }

        $itemid = $myobject->deleteItem();
        if (!empty($return_url)) {
            $this->ctl()->redirect($return_url);
        } else {
            $default = $this->mod()->getVar('default_view');
            $this->ctl()->redirect($this->mod()->getURL(
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
