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

function calendar_userapi_getmenulinks()
{
    xarVarFetch('cal_sdow','int::',$cal_sdow,xarModUserVars::get('calendar','cal_sdow'));
    xarVarFetch('cal_date','int::',$cal_date,xarLocaleFormatDate('%Y%m%d'));

    $menulinks[] = array('url'   => xarModURL('calendar','user','day',array('cal_date'=>$cal_date)),
                         'title' => xarML('Day'),
                         'label' => xarML('Day'));

    $menulinks[] = array('url'   => xarModURL('calendar','user','week',array('cal_date'=>$cal_date)),
                         'title' => xarML('Week'),
                         'label' => xarML('Week'));

    $menulinks[] = array('url'   => xarModURL('calendar','user','month',array('cal_date'=>$cal_date)),
                         'title' => xarML('Month'),
                         'label' => xarML('Month'));

    $menulinks[] = array('url'   => xarModURL('calendar','user','year',array('cal_date'=>$cal_date)),
                         'title' => xarML('Year'),
                         'label' => xarML('Year'));

    if(xarUser::isLoggedIn()) {
        $menulinks[] = array('url' => xarModURL('calendar','user','modifyconfig'),
                             'title' => xarML('Modify Config'),
                             'label' => xarML('Modify Config'));
    }

    return $menulinks;

}
?>
