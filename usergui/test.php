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

/**
 * calendar user test function
 * @extends MethodClass<UserGui>
 */
class TestMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Calendar Module
     * @package modules
     * @subpackage calendar module
     * @category Third Party Xaraya Module
     * @version 1.0.0
     * @copyright (C) copyright-placeholder
     * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
     * @author Marc Lutolf <mfl@netspan.ch>
     * @see UserGui::test()
     */
    public function __invoke(array $args = [])
    {
        /** @var UserApi $userapi */
        $userapi = $this->userapi();

        // some timing for now to see how fast|slow the parser is
        // @todo no idea where this is now
        include_once('Benchmark/Timer.php');
        $t = new \Benchmark_Timer();
        $t->start();
        // @todo use johngrogg/ics-parser or sabre/vobject package
        $ical = $userapi->factory('ical_parser');
        $t->setMarker('Class Instantiated');
        $this->var()->get('file', $file, 'str::');
        $t->setMarker('File Var Fetched');
        //$ical->setFile('code/modules/timezone/zoneinfo/America/Phoenix.ics');
        $ical->setFile($file);
        $t->setMarker('File Set');
        $ical->parse();
        $t->setMarker('Parsing Complete');

        $t->stop();

        ob_start();
        print_r($ical);
        $ical_out = ob_get_contents();
        ob_end_clean();

        $data = [
            'ical' => $ical_out,
            'profile' => $t->getOutput(),
        ];

        return $data;
    }
}
