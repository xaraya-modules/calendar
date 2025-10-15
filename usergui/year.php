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
use Xaraya\Modules\Calendar\UserApi;
use Xaraya\Modules\MethodClass;
use sys;

sys::import('xaraya.modules.method');

/**
 * calendar user year function
 * @extends MethodClass<UserGui>
 */
class YearMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup * @see UserGui::year()
     */

    public function __invoke(array $args = [])
    {
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        $data = $userapi->getUserDateTimeInfo();
        $Year = new \Calendar_Year($data['cal_year']);
        $Year->build(); // TODO: find a better way to handle this
        $data['Year'] = & $Year;
        $data['cal_sdow'] = $this->mod()->getVar('cal_sdow');
        return $data ;
    }
}
