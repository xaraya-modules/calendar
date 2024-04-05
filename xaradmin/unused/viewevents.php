<?php

function calendar_admin_viewevents(array $args = [], $context = null)
{
    if (!xarSecurity::check('EditCalendar')) {
        return;
    }
    $data['object'] = xarMod::apiFunc('dynamicdata', 'user', 'getobjectlist', ['name' => 'calendar_event']);
    $data['object']->getItems();
    return xarTpl::module('calendar', 'admin', 'view', $data);
}
