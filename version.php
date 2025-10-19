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

namespace Xaraya\Modules\Calendar;

class Version
{
    /**
     * Get module version information
     *
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            'name' => 'calendar',
            'id' => '7',
            'version' => '2.1.0',
            'displayname' => 'Calendar',
            'description' => 'Calendar System',
            'credits' => 'credits.txt',
            'help' => 'help.txt',
            'changelog' => 'changelog.txt',
            'license' => 'license.txt',
            'official' => 0,
            'author' => 'Roger Raymond and Xaraya calendar team',
            'contact' => 'http://xaraya.simiansynapse.com/',
            'admin' => true,
            'user' => true,
            'class' => 'Complete',
            'category' => 'Content',
            'namespace' => 'Xaraya\\Modules\\Calendar',
            'twigtemplates' => true,
            'dependencyinfo'
             => [
                 0
                  => [
                      'name' => 'Xaraya Core',
                      'version_ge' => '2.4.1',
                  ],
             ],
            'securityschema'
             => [
                 'calendar::event' => 'Event Title::Event ID',
                 'calendar::category' => 'Category Name::Category ID',
                 'calendar::topic' => 'Topic Name::Topic ID',
                 'calendar::user' => 'User Name::User ID',
                 'calendar::sharing' => 'User Name::User ID',
                 'calendar::' => '::',
             ],
        ];
    }
}
