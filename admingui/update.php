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

/**
 * calendar admin update function
 * @extends MethodClass<AdminGui>
 */
class UpdateMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see AdminGui::update()
     */

    public function __invoke(array $args = [])
    {
        extract($args);

        $this->var()->check('objectid', $objectid);
        $this->var()->check('itemid', $itemid);
        $this->var()->check('join', $join);
        $this->var()->check('table', $table);
        $this->var()->find('tplmodule', $tplmodule, 'isset', 'calendar');
        $this->var()->check('return_url', $return_url);
        $this->var()->find('preview', $preview, 'isset', 0);

        if (!$this->sec()->confirmAuthKey()) {
            return;
        }
        $myobject = $this->data()->getObject(['objectid' => $objectid,
            'join'     => $join,
            'table'    => $table,
            'itemid'   => $itemid, ]);
        $itemid = $myobject->getItem();
        // if we're editing a dynamic property, save its property type to cache
        // for correct processing of the configuration rule (ValidationProperty)
        if ($myobject->objectid == 2) {
            $this->mem()->set('dynamicdata', 'currentproptype', $myobject->properties['type']);
        }

        $isvalid = $myobject->checkInput([], 0, 'dd');

        // recover any session var information
        $data = $this->mod()->apiFunc('dynamicdata', 'user', 'sessioncontext', ['module' => $tplmodule]);
        extract($data);

        if (!empty($preview) || !$isvalid) {
            $data = array_merge($data, $this->mod()->apiFunc('dynamicdata', 'admin', 'menu'));
            $data['object'] = & $myobject;

            $data['objectid'] = $myobject->objectid;
            $data['itemid'] = $itemid;
            $data['authid'] = $this->sec()->genAuthKey();
            $data['preview'] = $preview;
            if (!empty($return_url)) {
                $data['return_url'] = $return_url;
            }

            // Makes this hooks call explictly from DD
            // $modinfo = $this->mod()->getInfo($myobject->moduleid);
            $modinfo = $this->mod()->getInfo(182);
            $item = [];
            foreach (array_keys($myobject->properties) as $name) {
                $item[$name] = $myobject->properties[$name]->value;
            }
            $item['module'] = $modinfo['name'];
            $item['itemtype'] = $myobject->itemtype;
            $item['itemid'] = $myobject->itemid;
            $hooks = [];
            $hooks = $this->mod()->callHooks('item', 'modify', $myobject->itemid, $item, $modinfo['name']);
            $data['hooks'] = $hooks;

            return $this->tpl()->module($tplmodule, 'user', 'modify', $data);
        }

        // Valid and not previewing, update the object

        $itemid = $myobject->updateItem();
        if (!isset($itemid)) {
            return;
        } // throw back

        // If we are here then the update is valid: reset the session var
        $this->session()->setVar('ddcontext.' . $tplmodule, ['tplmodule' => $tplmodule]);

        $item = $myobject->getFieldValues();
        $item['module'] = 'calendar';
        $item['itemtype'] = 1;
        $this->mod()->callHooks('item', 'update', $itemid, $item);

        if (!empty($return_url)) {
            $this->ctl()->redirect($return_url);
        } elseif ($myobject->objectid == 2) { // for dynamic properties, return to modifyprop
            $objectid = $myobject->properties['objectid']->value;
            $this->ctl()->redirect($this->ctl()->getModuleURL(
                'dynamicdata',
                'admin',
                'modifyprop',
                ['itemid' => $objectid]
            ));
        } else {
            $this->ctl()->redirect($this->ctl()->getModuleURL(
                'dynamicdata',
                'admin',
                'view',
                [
                    'itemid' => $objectid,
                    'tplmodule' => $tplmodule,
                ]
            ));
        }
        return true;
    }
}
