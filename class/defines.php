<?php

/**
 * @package modules\calendar
 * @category Xaraya Web Applications Framework
 * @version 2.5.7
 * @copyright see the html/credits.html file in this release
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link https://github.com/mikespub/xaraya-modules
**/

namespace Xaraya\Modules\Calendar;

/**
 * Defines constants for the calendar module (from xaruserapi/init.php)
 */
class Defines
{
    //=================================================================
    //  define constants used to make the code more readable
    //=================================================================
    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;

    public const TYPE_VEVENT = 0;
    public const TYPE_VTODO = 1;
    public const TYPE_VJOURNAL = 2;
    public const TYPE_VFREEBUSY = 3;
    public const TYPE_VALARM = 4;

    public const CUTYPE_INDIVIDUAL = 0;
    public const CUTYPE_GROUP = 1;
    public const CUTYPE_RESOURCE = 2;
    public const CUTYPE_ROOM = 3;
    public const CUTYPE_UNKNOWN = 4;

    public const ROLE_CHAIR = 0;
    public const ROLE_REQ_PARTICIPANT = 1;
    public const ROLE_OPT_PARTICIPANT = 2;
    public const ROLE_NON_PARTICIPANT = 3;

    public const PARTSTAT_NEEDS_ACTION = 0;
    public const PARTSTAT_ACCEPTED = 1;
    public const PARTSTAT_DECLINED = 2;
    public const PARTSTAT_TENTATIVE = 3;
    public const PARTSTAT_DELEGATED = 4;
    public const PARTSTAT_COMPLETED = 5;
    public const PARTSTAT_IN_PROCESS = 6;

    public const CLASS_PUBLIC = 0;
    public const CLASS_PRIVATE = 1;
    public const CLASS_CONFIDENTIAL = 2;

    public const RELTYPE_PARENT = 0;
    public const RELTYPE_CHILD = 1;
    public const RELTYPE_SIBLING = 2;

    public const ALARM_ACTION_AUDIO = 0;
    public const ALARM_ACTION_DISPLAY = 1;
    public const ALARM_ACTION_EMAIL = 2;
    public const ALARM_ACTION_PROCEDURE = 3;

    public const STATUS_TENTATIVE = 0; // vevent
    public const STATUS_CONFIRMED = 1; // vevent
    public const STATUS_CANCELLED = 2; // vevent, vtodo, vjournal
    public const STATUS_NEEDS_ACTION = 3; // vtodo
    public const STATUS_COMPLETED = 4; // vtodo
    public const STATUS_IN_PROCESS = 5; // vtodo
    public const STATUS_DRAFT = 7; // vjournal
    public const STATUS_FINAL = 8; // vjournal

    public const TRANSP_OPAQUE = 0;
    public const TRANSP_TRANSPARENT = 1;



    //define('_AM_VAL =              1;
    //define('_PM_VAL =              2;
    //define('_ACTION_DELETE =       4;
    //define('_ACTION_EDIT =         2;
    //define('_EVENT_APPROVED =      1;
    //define('_EVENT_QUEUED =        0;
    //define('_EVENT_HIDDEN =       -1;
    // $event_repeat

    public const NO_REPEAT = 0;
    public const REPEAT = 1;
    public const REPEAT_ON = 2;

    // $event_repeat_freq
    public const REPEAT_EVERY = 1;
    public const REPEAT_EVERY_OTHER = 2;
    public const REPEAT_EVERY_THIRD = 3;
    public const REPEAT_EVERY_FOURTH = 4;

    // $event_repeat_freq_type
    public const REPEAT_EVERY_DAY = 0;
    public const REPEAT_EVERY_WEEK = 1;
    public const REPEAT_EVERY_MONTH = 2;
    public const REPEAT_EVERY_YEAR = 3;

    // $event_repeat_on_num
    public const REPEAT_ON_1ST = 1;
    public const REPEAT_ON_2ND = 2;
    public const REPEAT_ON_3RD = 3;
    public const REPEAT_ON_4TH = 4;
    public const REPEAT_ON_LAST = 5;

    // $event_repeat_on_day
    public const REPEAT_ON_SUN = 0;
    public const REPEAT_ON_MON = 1;
    public const REPEAT_ON_TUE = 2;
    public const REPEAT_ON_WED = 3;
    public const REPEAT_ON_THU = 4;
    public const REPEAT_ON_FRI = 5;
    public const REPEAT_ON_SAT = 6;

    // $event_repeat_on_freq
    //public const REPEAT_ON_MONTH =      1;
    //public const REPEAT_ON_2MONTH =     2;
    //public const REPEAT_ON_3MONTH =     3;
    //public const REPEAT_ON_4MONTH =     4;
    //public const REPEAT_ON_6MONTH =     6;
    //public const REPEAT_ON_YEAR =       12;

    // event sharing values
    public const SHARING_PRIVATE = 0;
    public const SHARING_PUBLIC = 1;
    public const SHARING_BUSY = 2;
    public const SHARING_GLOBAL = 3;
}
