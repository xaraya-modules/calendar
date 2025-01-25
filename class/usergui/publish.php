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
use xarVar;
use xarModVars;
use xarUser;
use xarSecurity;
use xarMod;
use xarLog;
use sys;
use BadParameterException;

sys::import('xaraya.modules.method');

/**
 * calendar user publish function
 * @extends MethodClass<UserGui>
 */
class PublishMethod extends MethodClass
{
    /** functions imported by bermuda_cleanup */

    /**
     * Publish a calendar
     * @see UserGui::publish()
     */
    public function __invoke(array $args = [])
    {
        extract($args);
        /** @var UserApi $userapi */
        $userapi = $this->userapi();
        $this->var()->find('calid', $calid, 'id', 0);
        $this->var()->find('calname', $calname, 'str:1:', '');

        // test
        $this->mod()->setVar('SupportShortURLs', 1);

        // TODO: security et al.

        if (!empty($calid) || !empty($calname)) {
            /* TEST: protect remote calendar access with basic authentication
                // cfr. notes at http://www.php.net/features.http-auth for IIS or CGI support
                    if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ||
                        // is this a valid user/password ?
                        !xarUser::logIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
                        // does this user have access to this calendar ?
                        !xarSecurity::check('ViewCalendar',0,'All',$calname)) {

                        $realm = xarModVars::get('themes','SiteName');
                        header('WWW-Authenticate: Basic realm="'.$realm.'"');
                        //header('HTTP/1.0 401 Unauthorized');
                        header("Status: 401 Access Denied");
                        echo $this->ml('You must enter a valid username and password to access this calendar');
                        $this->exit();
                     }
            */
            $calendars = $userapi->get(['calid' => $calid,
                    'calname' => $calname, ]
            );
            if (!isset($calendars)) {
                return;
            }

            // we found a calendar
            if (count($calendars) == 1) {
                if (empty($calendars[0]['cpath'])) {
                    // TODO: retrieve entries from database and create ics file
                } else {
                    $curdir = sys::varpath() . '/calendar';
                    $curfile = $curdir . '/' . $calendars[0]['cpath'];
                    if (file_exists($curfile) && filesize($curfile) > 0) {
                        if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
                            // return the .ics file
                            header('Content-Type: text/calendar');
                            @readfile($curfile);

                            // TODO: use webdavserver instead ?
                            // Cfr. phpicalendar/calendars/publish.php (doesn't seem to work for PHP < 4.3)
                            // publishing
                        } else {
                            // get calendar data
                            $data = '';
                            if ($fp = fopen('php://input', 'r')) {
                                while ($chunk = fgets($fp, 4096)) {
                                    $data .= $chunk;
                                }
                                /*
                                while(!@feof($fp))
                                {
                                    $data .= fgets($fp,4096);
                                }
                                */
                                @fclose($fp);
                            } else {
                                $this->log()->warning('failed opening standard input');
                            }

                            if (!empty($data)) {
                                //$this->log()->message($data);
                                // write to file
                                if ($fp = fopen($curfile, 'w+')) {
                                    fputs($fp, $data, strlen($data));
                                    @fclose($fp);
                                } else {
                                    $this->log()->warning('couldnt open file ' . $curfile);
                                }
                            } else {
                                $this->log()->warning('failed getting any data');
                            }
                        }
                        // we're done here
                        $this->exit();
                    }
                }
            }
        }
        $data = [];
        $data['calendars'] = $userapi->getall();

        return $data;
    }
}
