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
use xarVar;
use xarSec;
use xarMod;
use xarModHooks;
use xarTpl;
use xarSession;
use xarController;
use DataObjectFactory;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar admin create function
 * @extends MethodClass<AdminGui>
 */
class CreateMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see AdminGui::create()
     */

    public function __invoke(array $args = [])
    {
        extract($args);

        $this->var()->check('objectid', $objectid);
        $this->var()->find('itemid', $itemid, 'isset', 0);
        $this->var()->find('preview', $preview, 'isset', 0);
        $this->var()->check('return_url', $return_url);
        $this->var()->check('join', $join);
        $this->var()->check('table', $table);
        $this->var()->check('template', $template);
        $this->var()->find('tplmodule', $tplmodule, 'isset', 'calendar');

        if (!$this->sec()->confirmAuthKey()) {
            return;
        }

        $myobject = $this->data()->getObject(['objectid' => $objectid,
            'itemid'   => $itemid, ]);
        $isvalid = $myobject->checkInput();

        // recover any session var information
        $data = $this->mod()->apiFunc('dynamicdata', 'user', 'sessioncontext', ['module' => $tplmodule]);
        extract($data);

        if (!empty($preview) || !$isvalid) {
            $data = array_merge($data, $this->mod()->apiFunc('dynamicdata', 'admin', 'menu'));

            $data['object'] = & $myobject;

            $data['authid'] = $this->sec()->genAuthKey();
            $data['preview'] = $preview;
            if (!empty($return_url)) {
                $data['return_url'] = $return_url;
            }

            // Makes this hooks call explictly from DD
            //$modinfo = $this->mod()->getInfo($myobject->moduleid);
            $modinfo = $this->mod()->getInfo(182);
            $item = [];
            foreach (array_keys($myobject->properties) as $name) {
                $item[$name] = $myobject->properties[$name]->value;
            }
            $item['module'] = $modinfo['name'];
            $item['itemtype'] = $myobject->itemtype;
            $item['itemid'] = $myobject->itemid;
            $hooks = [];
            $hooks = xarModHooks::call('item', 'new', $myobject->itemid, $item, $modinfo['name']);
            $data['hooks'] = $hooks;

            if (!isset($template)) {
                $template = $myobject->name;
            }
            $data['context'] ??= $this->getContext();
            return $this->tpl()->module($tplmodule, 'user', 'new', $data, $template);
        }

        $itemid = $myobject->createItem();

        // If we are here then the create is valid: reset the session var
        $this->session()->setVar('ddcontext.' . $tplmodule, ['tplmodule' => $tplmodule]);

        if (empty($itemid)) {
            return;
        } // throw back

        $item = $myobject->getFieldValues();
        $item['module'] = 'calendar';
        $item['itemtype'] = 1;
        xarModHooks::call('item', 'create', $itemid, $item);

        if (!empty($return_url)) {
            $this->ctl()->redirect($return_url);
        } else {
            $this->ctl()->redirect($this->ctl()->getModuleURL(
                'dynamicdata',
                'admin',
                'view',
                [
                    'itemid' => $myobject->objectid,
                    'tplmodule' => $tplmodule,
                ]
            ));
        }
        return true;
    }
}
